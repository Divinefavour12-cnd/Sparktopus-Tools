<x-application-tools-wrapper>
    <x-tool-wrapper :tool="$tool">
        <x-ad-slot :advertisement="get_advert_model('above-form')" />
        <x-form method="post" :route="route('tool.show', $tool->slug)" enctype="multipart/form-data" id="image-upload-form">
            @csrf
            <div class="row">
                <div class="col-md-12 mt-3 mb-3">
                    <x-upload-wrapper :max-files="$tool->no_file_tool ?? 1" :max-size="$tool->fs_tool ?? 1" :accept="$extensions" input-name="images[]"
                        :file-title="__('tools.dropImageHereTitle')" :file-label="__('tools.convertToSVGDesc')" />
                    <x-input-error :messages="$errors->get('file')" class="mt-2" />
                </div>
            </div>
            <x-ad-slot :advertisement="get_advert_model('below-form')" />
            <div class="row">
                <div class="col-md-12 text-end">
                    <x-button type="submit" class="btn btn-outline-primary rounded-pill" id="convert-btn">
                        @lang('tools.convertToSvg')
                    </x-button>
                </div>
            </div>
        </x-form>
    </x-tool-wrapper>
    
    <div class="tool-results-wrapper d-none" id="results-section">
        <x-ad-slot :advertisement="get_advert_model('above-result')" />
        <x-page-wrapper :title="__('common.result')">
            <div class="row">
                <div class="col-md-12">
                    <div class="progress" style="height: 3px;">
                        <div id="conversion-progress" class="progress-bar bg-success" role="progressbar"
                            aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                <div class="col-md-12">
                    <table class="table table-style">
                        <thead>
                            <tr>
                                <th width="75">#</th>
                                <th>@lang('common.fileName')</th>
                                <th>SVG Output</th>
                                <th width="100">@lang('common.size')</th>
                                <th width="175"></th>
                            </tr>
                        </thead>
                        <tbody id="processing-files">
                            <!-- Processed files will appear here -->
                        </tbody>
                    </table>
                </div>
                <div class="col-md-12 text-end">
                    <button type="button" class="btn btn-outline-primary rounded-pill d-none" id="download-all-btn">
                        @lang('tools.downloadAll')
                    </button>
                    <x-reload-button :link="route('tool.show', ['tool' => $tool->slug])" />
                </div>
            </div>
        </x-page-wrapper>
        <x-ad-slot :advertisement="get_advert_model('below-result')" />
    </div>
    
    <x-tool-content :tool="$tool" />

    @push('page_styles')
    <style>
        #app-loader, .loading-overlay, .loading, .overlay, .modal-overlay, .swal2-container {
            display: none !important;
            visibility: hidden !important;
            opacity: 0 !important;
        }
    </style>
    @endpush

    @push('page_scripts')
    <!-- SweetAlert2 CSS and JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        // Prevent any overlays from appearing
        document.addEventListener("DOMContentLoaded", function() {
            // Immediately hide any existing loaders
            const hideLoaders = () => {
                document.querySelectorAll('#app-loader, .loading-overlay, .loading, .overlay, .modal-overlay').forEach(el => {
                    el.style.display = 'none';
                    el.style.visibility = 'hidden';
                    el.style.opacity = '0';
                    el.remove(); // Remove from DOM to prevent reappearance
                });
            };
            hideLoaders();

            // Use MutationObserver to detect and remove loaders added dynamically
            const observer = new MutationObserver((mutations) => {
                mutations.forEach((mutation) => {
                    if (mutation.addedNodes.length) {
                        mutation.addedNodes.forEach((node) => {
                            if (node.nodeType === 1) { // Element node
                                if (node.matches('#app-loader, .loading-overlay, .loading, .overlay, .modal-overlay') ||
                                    node.querySelector('#app-loader, .loading-overlay, .loading, .overlay, .modal-overlay')) {
                                    hideLoaders();
                                }
                            }
                        });
                    }
                });
            });

            observer.observe(document.body, {
                childList: true,
                subtree: true
            });

            const SVGConverter = {
                files: [],
                processedFiles: [],
                maxFiles: 20,
                currentIndex: 0,
                
                init: function() {
                    this.attachEvents();
                },
                
                attachEvents: function() {
                    const form = document.getElementById('image-upload-form');
                    const fileInput = form.querySelector('input[name="images[]"]');
                    
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();
                        hideLoaders(); // Ensure no loaders appear on submit
                        SVGConverter.handleFormSubmit(fileInput.files);
                    });
                    
                    document.getElementById('download-all-btn').addEventListener('click', function() {
                        SVGConverter.downloadAllFiles();
                    });
                },
                
                handleFormSubmit: function(fileList) {
                    if (fileList.length === 0) {
                        this.showAlert(
                            'No Files Selected', 
                            'Please select at least one image file to convert.',
                            'warning'
                        );
                        return;
                    }
                    
                    // Validate file types before proceeding
                    const validImageTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/bmp', 'image/webp'];
                    const invalidFiles = Array.from(fileList).filter(file => !validImageTypes.includes(file.type));
                    
                    if (invalidFiles.length > 0) {
                        this.showAlert(
                            'Invalid File Types', 
                            'Some files are not valid images. Please select only image files.',
                            'warning'
                        );
                        return;
                    }
                    
                    this.files = Array.from(fileList);
                    this.processedFiles = [];
                    this.currentIndex = 0;
                    
                    // Show results section immediately
                    document.getElementById('results-section').classList.remove('d-none');
                    document.getElementById('processing-files').innerHTML = '';
                    document.getElementById('conversion-progress').style.width = '0%';
                    document.getElementById('download-all-btn').classList.add('d-none');
                    
                    // Scroll to results section
                    document.getElementById('results-section').scrollIntoView({
                        behavior: 'smooth'
                    });
                    
                    // Start processing files
                    this.processNextFile();
                },
                
                processNextFile: function() {
                    if (this.currentIndex >= this.files.length || this.currentIndex >= this.maxFiles) {
                        if (this.processedFiles.length > 1) {
                            document.getElementById('download-all-btn').classList.remove('d-none');
                        }
                        
                        // Show completion alert
                        this.showAlert(
                            'Conversion Complete',
                            `Successfully converted ${this.processedFiles.length} ${this.processedFiles.length === 1 ? 'image' : 'images'} to SVG format.`,
                            'success'
                        );
                        
                        // Scroll to results section
                        document.getElementById('results-section').scrollIntoView({
                            behavior: 'smooth'
                        });
                        
                        return;
                    }
                    
                    const file = this.files[this.currentIndex];
                    this.displayFileRow(file, this.currentIndex);
                    this.convertToSVG(file, this.currentIndex);
                },
                
                showAlert: function(title, message, icon = 'info') {
                    Swal.fire({
                        title: title,
                        text: message,
                        icon: icon,
                        toast: true,
                        position: 'bottom-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer);
                            toast.addEventListener('mouseleave', Swal.resumeTimer);
                        }
                    });
                },
                
                displayFileRow: function(file, index) {
                    const tbody = document.getElementById('processing-files');
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${index + 1}</td>
                        <td><div class="mw-350 text-truncate fw-bold">${file.name}</div></td>
                        <td><div class="mw-350 text-truncate fw-bold" id="new-name-${index}"></div></td>
                        <td id="new-size-${index}"></td>
                        <td id="file-cursor-${index}">
                            <div class="badge bg-secondary">Processing</div>
                        </td>
                    `;
                    tbody.appendChild(row);
                },
                
                convertToSVG: function(file, index) {
                    // Create a FileReader to read the image
                    const reader = new FileReader();
                    
                    reader.onload = (e) => {
                        const img = new Image();
                        
                        img.onload = () => {
                            try {
                                // Create SVG using Potrace-like algorithm (simplified for demonstration)
                                const svgContent = this.createSVGFromImage(img);
                                const svgBlob = new Blob([svgContent], { type: 'image/svg+xml' });
                                const svgUrl = URL.createObjectURL(svgBlob);
                                const svgFileName = file.name.split('.')[0] + '.svg';
                                
                                // Update UI
                                this.updateProgress(index);
                                this.displayResult(svgUrl, svgFileName, svgBlob.size, index);
                                
                                // Store processed file info
                                this.processedFiles.push({
                                    originalName: file.name,
                                    svgName: svgFileName,
                                    url: svgUrl,
                                    size: svgBlob.size
                                });
                            } catch (error) {
                                console.error("Error converting image to SVG:", error);
                                // Handle error in UI
                                document.getElementById('file-cursor-' + index).innerHTML = 
                                    '<span class="badge bg-danger">Failed to convert</span>';
                            }
                            
                            // Process next file regardless of success/failure of current conversion
                            this.currentIndex++;
                            this.processNextFile();
                        };
                        
                        img.onerror = () => {
                            console.error("Error loading image");
                            document.getElementById('file-cursor-' + index).innerHTML = 
                                '<span class="badge bg-danger">Failed to load</span>';
                            
                            // Continue with next file even if this one failed
                            this.currentIndex++;
                            this.processNextFile();
                        };
                        
                        // Set src to trigger load
                        img.src = e.target.result;
                    };
                    
                    reader.onerror = () => {
                        console.error("Error reading file");
                        document.getElementById('file-cursor-' + index).innerHTML = 
                            '<span class="badge bg-danger">Failed to read</span>';
                        
                        // Continue with next file
                        this.currentIndex++;
                        this.processNextFile();
                    };
                    
                    // Start reading the file
                    reader.readAsDataURL(file);
                },
                
                createSVGFromImage: function(img) {
                    // This is a simplified placeholder for actual image-to-SVG conversion
                    // In a real implementation, you would use a library like Potrace.js
                    // or a more sophisticated algorithm to trace the image
                    
                    const width = img.width;
                    const height = img.height;
                    
                    // Create a simple SVG with the image embedded as a data URL
                    return `<svg xmlns="http://www.w3.org/2000/svg" width="${width}" height="${height}" viewBox="0 0 ${width} ${height}">
                        <title>Converted from ${img.src.substring(0, 20)}...</title>
                        <image href="${img.src}" width="${width}" height="${height}" />
                        <!-- In a real implementation, vector paths would be here instead of embedding the image -->
                    </svg>`;
                },
                
                updateProgress: function(index) {
                    const progress = ((index + 1) / Math.min(this.files.length, this.maxFiles)) * 100;
                    document.getElementById('conversion-progress').style.width = progress + '%';
                },
                
                displayResult: function(svgUrl, svgFileName, size, index) {
                    const downloadBtn = `
                        <button class="btn btn-outline-primary rounded-pill download-file-btn" type="button" 
                            data-url="${svgUrl}" data-filename="${svgFileName}">
                            @lang('common.download')
                        </button>
                    `;
                    
                    document.getElementById('file-cursor-' + index).innerHTML = downloadBtn;
                    document.getElementById('new-size-' + index).innerHTML = this.formatFileSize(size);
                    document.getElementById('new-name-' + index).innerHTML = svgFileName;
                    
                    // Attach event to the new download button
                    document.querySelector(`#file-cursor-${index} .download-file-btn`).addEventListener('click', function(e) {
                        SVGConverter.downloadFile(e.target.dataset.url, e.target.dataset.filename);
                    });
                },
                
                downloadFile: function(url, filename) {
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = filename;
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                },
                
                downloadAllFiles: function() {
                    // In a real implementation, you might want to create a zip file
                    // For simplicity, we'll just trigger individual downloads
                    this.processedFiles.forEach(file => {
                        this.downloadFile(file.url, file.svgName);
                    });
                },
                
                formatFileSize: function(bytes) {
                    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
                    if (bytes === 0) return '0 Byte';
                    const i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
                    return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
                }
            };
            
            SVGConverter.init();
        });
    </script>
    @endpush
</x-application-tools-wrapper>