<?php

use App\Helpers\Classes\ArtisanApi;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\TagsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PlansController;
use App\Http\Controllers\Admin\PostsController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\ToolsController;
use App\Http\Controllers\Admin\SystemController;
use App\Http\Controllers\Admin\ThemesController;
use App\Http\Controllers\Admin\UpdateController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SitemapController;
use App\Http\Controllers\Admin\WidgetsController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PermissionsController;
use App\Http\Controllers\Admin\AdvertisementsController;
use App\Http\Controllers\Admin\FeedbackController as AdminFeedbackController;

require __DIR__ . '/admin_auth.php';
Route::group(
    ['middleware' => ['AdminTheme', 'auth:admin']],
    function () {
        Route::post('admin/register-item', [ArtisanApi::class, 'register'])->name('system.register-item')->can('application operations');
    }
);

Route::group(
    ['prefix' => env('APP_ADMIN_PREFIX', 'admin'),  'middleware' => ['auth:admin']],
    function () {
        Route::get('/', [\App\Http\Controllers\SparkAdmin\DashboardController::class, 'index'])->name('admin.home');
        Route::get('/dashboard', [\App\Http\Controllers\SparkAdmin\DashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('/spark-dashboard', [\App\Http\Controllers\SparkAdmin\DashboardController::class, 'index'])->name('spark-admin.dashboard');

        // Custom SparkAdmin Feedback Routes
        Route::get('/feedback', [\App\Http\Controllers\SparkAdmin\FeedbackController::class, 'index'])->name('spark-admin.feedback.index');
        Route::get('/feedback/{feedback}', [\App\Http\Controllers\SparkAdmin\FeedbackController::class, 'show'])->name('spark-admin.feedback.show');
        Route::post('/feedback/{feedback}/status', [\App\Http\Controllers\SparkAdmin\FeedbackController::class, 'updateStatus'])->name('spark-admin.feedback.status');
        Route::delete('/feedback/{feedback}', [\App\Http\Controllers\SparkAdmin\FeedbackController::class, 'destroy'])->name('spark-admin.feedback.destroy');

        // Custom SparkAdmin Subscription Routes
        Route::get('/subscriptions', [\App\Http\Controllers\SparkAdmin\SubscriptionController::class, 'index'])->name('spark-admin.subscriptions.index');
        Route::post('/subscriptions/{transaction}/status', [\App\Http\Controllers\SparkAdmin\SubscriptionController::class, 'updateStatus'])->name('spark-admin.subscriptions.status');

        // Custom SparkAdmin Update Routes (Rebranding Blog)
        Route::get('/updates', [\App\Http\Controllers\SparkAdmin\UpdateController::class, 'index'])->name('spark-admin.updates.index');
        Route::get('/updates/create', [\App\Http\Controllers\SparkAdmin\UpdateController::class, 'create'])->name('spark-admin.updates.create');
        Route::post('/updates', [\App\Http\Controllers\SparkAdmin\UpdateController::class, 'store'])->name('spark-admin.updates.store');
        Route::get('/updates/{post}/edit', [\App\Http\Controllers\SparkAdmin\UpdateController::class, 'edit'])->name('spark-admin.updates.edit');
        Route::post('/updates/{post}/update', [\App\Http\Controllers\SparkAdmin\UpdateController::class, 'update'])->name('spark-admin.updates.update');
        Route::delete('/updates/{post}', [\App\Http\Controllers\SparkAdmin\UpdateController::class, 'destroy'])->name('spark-admin.updates.destroy');

        // Custom SparkAdmin Profile Routes
        Route::get('/profile', [\App\Http\Controllers\SparkAdmin\ProfileController::class, 'index'])->name('spark-admin.profile.index');
        Route::post('/profile', [\App\Http\Controllers\SparkAdmin\ProfileController::class, 'update'])->name('spark-admin.profile.update');
        Route::post('/profile/theme', [\App\Http\Controllers\SparkAdmin\ProfileController::class, 'updateTheme'])->name('spark-admin.profile.update-theme');

        // Custom SparkAdmin User Routes
        Route::get('/users', [\App\Http\Controllers\SparkAdmin\UserController::class, 'index'])->name('spark-admin.users.index');
        Route::get('/users/{user}', [\App\Http\Controllers\SparkAdmin\UserController::class, 'show'])->name('spark-admin.users.show');
        Route::post('/users/{user}/suspend', [\App\Http\Controllers\SparkAdmin\UserController::class, 'suspend'])->name('spark-admin.users.suspend');
        Route::post('/users/{user}/unsuspend', [\App\Http\Controllers\SparkAdmin\UserController::class, 'unsuspend'])->name('spark-admin.users.unsuspend');
        Route::post('/users/{user}/ban', [\App\Http\Controllers\SparkAdmin\UserController::class, 'ban'])->name('spark-admin.users.ban');
        Route::post('/users/{user}/reset-usage', [\App\Http\Controllers\SparkAdmin\UserController::class, 'resetUsage'])->name('spark-admin.users.reset-usage');
        Route::post('/users/{user}/upgrade/{plan}', [\App\Http\Controllers\SparkAdmin\UserController::class, 'upgrade'])->name('spark-admin.users.upgrade');
        Route::delete('/users/{user}', [\App\Http\Controllers\SparkAdmin\UserController::class, 'destroy'])->name('spark-admin.users.destroy');

        // Custom SparkAdmin Tools Routes
        Route::get('/tools', [\App\Http\Controllers\SparkAdmin\ToolsController::class, 'index'])->name('spark-admin.tools.index');
        Route::get('/tools/{tool}/edit', [\App\Http\Controllers\SparkAdmin\ToolsController::class, 'edit'])->name('spark-admin.tools.edit');
        Route::post('/tools/{tool}/update', [\App\Http\Controllers\SparkAdmin\ToolsController::class, 'update'])->name('spark-admin.tools.update');
        Route::get('/tools/status/{tool}', [\App\Http\Controllers\SparkAdmin\ToolsController::class, 'statusChange'])->name('spark-admin.tools.status');
        Route::get('/tools/homepage-selector', [\App\Http\Controllers\SparkAdmin\ToolsController::class, 'homePage'])->name('spark-admin.tools.homepage');
        Route::post('/tools/set-home/{tool}', [\App\Http\Controllers\SparkAdmin\ToolsController::class, 'setHome'])->name('spark-admin.tools.set-home');

        // Custom SparkAdmin Advertisement Routes
        Route::get('/ads', [\App\Http\Controllers\SparkAdmin\AdvertisementController::class, 'index'])->name('spark-admin.advertisement.index');
        Route::get('/ads/create/{type}', [\App\Http\Controllers\SparkAdmin\AdvertisementController::class, 'create'])->name('spark-admin.advertisement.create');
        Route::post('/ads', [\App\Http\Controllers\SparkAdmin\AdvertisementController::class, 'store'])->name('spark-admin.advertisement.store');
        Route::get('/ads/{advertisement}/edit', [\App\Http\Controllers\SparkAdmin\AdvertisementController::class, 'edit'])->name('spark-admin.advertisement.edit');
        Route::post('/ads/{advertisement}/update', [\App\Http\Controllers\SparkAdmin\AdvertisementController::class, 'update'])->name('spark-admin.advertisement.update');
        Route::get('/ads/status/{advertisement}', [\App\Http\Controllers\SparkAdmin\AdvertisementController::class, 'statusChange'])->name('spark-admin.advertisement.status');
        Route::delete('/ads/{advertisement}', [\App\Http\Controllers\SparkAdmin\AdvertisementController::class, 'destroy'])->name('spark-admin.advertisement.destroy');

        // Custom SparkAdmin Setting Routes
        Route::get('/settings', [\App\Http\Controllers\SparkAdmin\SettingController::class, 'index'])->name('spark-admin.settings.index');
        Route::post('/settings', [\App\Http\Controllers\SparkAdmin\SettingController::class, 'update'])->name('spark-admin.settings.update');
        // Custom SparkAdmin RBAC Routes
        Route::get('/access-control/roles', [\App\Http\Controllers\SparkAdmin\RolesController::class, 'index'])->name('spark-admin.roles.index');
        Route::post('/access-control/roles', [\App\Http\Controllers\SparkAdmin\RolesController::class, 'store'])->name('spark-admin.roles.store');
        Route::post('/access-control/roles/{role}/update', [\App\Http\Controllers\SparkAdmin\RolesController::class, 'update'])->name('spark-admin.roles.update');
        Route::delete('/access-control/roles/{role}', [\App\Http\Controllers\SparkAdmin\RolesController::class, 'destroy'])->name('spark-admin.roles.destroy');

        Route::get('/access-control/permissions', [\App\Http\Controllers\SparkAdmin\PermissionsController::class, 'index'])->name('spark-admin.permissions.index');
        Route::post('/access-control/permissions', [\App\Http\Controllers\SparkAdmin\PermissionsController::class, 'store'])->name('spark-admin.permissions.store');

        Route::get('/access-control/admins', [\App\Http\Controllers\SparkAdmin\AdminUserController::class, 'index'])->name('spark-admin.admins.index');
        Route::post('/access-control/admins', [\App\Http\Controllers\SparkAdmin\AdminUserController::class, 'store'])->name('spark-admin.admins.store');
        Route::post('/access-control/admins/{admin}/update', [\App\Http\Controllers\SparkAdmin\AdminUserController::class, 'update'])->name('spark-admin.admins.update');
        Route::delete('/access-control/admins/{admin}', [\App\Http\Controllers\SparkAdmin\AdminUserController::class, 'destroy'])->name('spark-admin.admins.destroy');

        Route::get('pages', [PageController::class, 'index'])->name('admin.pages')->can('manage page');
        Route::get('pages/create', [PageController::class, 'create'])->name('admin.pages.create')->can('create page');
        Route::post('pages/create', [PageController::class, 'store'])->name('admin.pages.store')->can('create page');
        Route::get('pages/{page}/edit', [PageController::class, 'edit'])->name('admin.pages.edit')->can('edit page');
        Route::post('pages/{page}/edit', [PageController::class, 'update'])->name('admin.pages.update')->can('edit page');
        Route::delete('pages/{page}', [PageController::class, 'destroy'])->name('admin.pages.destroy')->can('delete page');

/*
        Route::get('updates', [PostsController::class, 'index'])->name('admin.updates')->can('manage post');
        Route::get('updates/create', [PostsController::class, 'create'])->name('admin.updates.create')->can('create post');
        Route::post('updates/create', [PostsController::class, 'store'])->name('admin.updates.store')->can('create post');
        Route::get('updates/{post}/edit', [PostsController::class, 'edit'])->name('admin.updates.edit')->can('edit post');
        Route::post('updates/{post}/edit', [PostsController::class, 'update'])->name('admin.updates.update')->can('edit post');
        Route::delete('updates/{post}', [PostsController::class, 'destroy'])->name('admin.updates.destroy')->can('delete post');
        Route::get('updates/{post}/{id}', [PostsController::class, 'featured'])->name('admin.updates.featured')->can('manage post');
*/

        Route::get('tags', [TagsController::class, 'index'])->name('admin.tags')->can('view tag');
        Route::post('tags/create', [TagsController::class, 'store'])->name('admin.tags.store')->can('create tag');
        Route::get('tags/{tag}/edit', [TagsController::class, 'edit'])->name('admin.tags.edit')->can('edit tag');
        Route::post('tags/{tag}/edit', [TagsController::class, 'update'])->name('admin.tags.update')->can('edit tag');
        Route::delete('tags/{tag}', [TagsController::class, 'destroy'])->name('admin.tags.destroy')->can('delete tag');

        Route::get('categories/{type?}', [CategoryController::class, 'index'])->name('admin.categories')->can('view category');
        Route::post('categories/create', [CategoryController::class, 'store'])->name('admin.categories.store')->can('create category');
        Route::post('categories/{category}/edit', [CategoryController::class, 'update'])->name('admin.categories.update')->can('edit category');
        Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy')->can('delete category');
        Route::get('categories/{category}/edit', [CategoryController::class, 'edit'])->name('admin.categories.edit')->can('edit category');
        Route::put('categories/sort', [CategoryController::class, 'sort'])->name('admin.categories.sort')->can('edit category');

        Route::get('roles/{role?}', [RolesController::class, 'index'])->name('admin.roles')->can('manage roles');
        Route::post('roles/create', [RolesController::class, 'store'])->name('admin.roles.store')->can('create roles');
        Route::post('roles/{role}/update', [RolesController::class, 'update'])->name('admin.roles.update')->can('edit roles');
        Route::post('roles/edit', [RolesController::class, 'edit'])->name('admin.roles.edit')->can('edit roles');
        Route::delete('roles/{role}', [RolesController::class, 'destroy'])->name('admin.roles.destroy')->can('delete roles');
        Route::post('roles/unassign/action', [RolesController::class, 'roleAction'])->name('admin.role.action')->can('manage roles');
        Route::post('roles/users/get/{role}', [RolesController::class, 'getUsers'])->name('admin.role.getUsers')->can('manage roles');

/*
        Route::get('users', [UserController::class, 'index'])->name('admin.users')->can('manage users');
        Route::get('users/trashed', [UserController::class, 'trashed'])->name('admin.users.trashed')->can('manage users');
        Route::post('users/create', [UserController::class, 'store'])->name('admin.users.store')->can('create users');
        Route::post('users/update', [UserController::class, 'update'])->name('admin.users.update')->can('edit users');
        Route::post('users/edit', [UserController::class, 'edit'])->name('admin.users.edit')->can('edit users');
        Route::get('users/status/change/{id}/{status}', [UserController::class, 'statusChange'])->name('admin.users.status.change')->can('manage users');
        Route::delete('users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy')->can('delete users');
        Route::put('users/{user}', [UserController::class, 'restore'])->name('admin.users.restore')->can('edit users');
        Route::delete('users/{user}/delete', [UserController::class, 'delete'])->name('admin.users.delete')->can('delete users');
*/

        Route::get('permissions/{user?}', [PermissionsController::class, 'index'])->name('admin.permissions')->can('manage permissions');
        Route::post('permissions/create', [PermissionsController::class, 'store'])->name('admin.permissions.store')->can('manage permissions');
        Route::post('permissions/{user}/edit', [PermissionsController::class, 'update'])->name('admin.permissions.update')->can('manage permissions');
        Route::get('permissions/{user}/edit', [PermissionsController::class, 'update'])->name('admin.permissions.edit')->can('manage permissions');

        // Menu Manager
        Route::get('/menus/{menu?}', [MenuController::class, 'index'])->name('admin.menus')->can('manage menus');
        Route::post('/menu/create', [MenuController::class, 'store'])->name('admin.menus.create')->can('create menus');
        Route::post('/menus/{menu}/add-items', [MenuController::class, 'addItems'])->name('admin.menus.add-items')->can('manage menus');
        Route::post('/menus/{menu}/update', [MenuController::class, 'update'])->name('admin.menus.update')->can('edit menus');
        Route::delete('/menu/{menu}/delete', [MenuController::class, 'destroy'])->name('admin.menus.destroy')->can('delete menus');
        Route::delete('/menu/{menu}/{item}/delete', [MenuController::class, 'destroyItem'])->name('admin.menus.item.destroy')->can('delete menus');

        // Settings
/*
        Route::get('/settings', [SettingsController::class, 'index'])->name('admin.settings')->can('manage settings');
        Route::post('/settings', [SettingsController::class, 'update'])->name('admin.settings.update')->can('manage settings');
*/

/*
        //tools
        Route::get('/tools', [ToolsController::class, 'index'])->name('admin.tools')->can('manage tools');
        Route::get('/tools/home-page', [ToolsController::class, 'homePage'])->name('admin.tools.home-page')->can('manage tools');
        Route::get('tools/{tool}/edit', [ToolsController::class, 'edit'])->name('admin.tools.edit')->can('edit tools');
        Route::post('tools/{tool}/edit', [ToolsController::class, 'update'])->name('admin.tools.update')->can('edit tools');
        Route::get('tools/status/change/{id}/{status}', [ToolsController::class, 'statusChange'])->name('admin.tools.status.change')->can('manage tools');
        Route::post('tools/bulk-action', [ToolsController::class, 'bulkAction'])->name('admin.tools.bulk')->can('edit tools');
*/

        //profile
        Route::get('user/profile', [ProfileController::class, 'index'])->name('admin.profile')->can('manage profile');
        Route::get('user/password', [ProfileController::class, 'password'])->name('admin.password')->can('manage profile');
        Route::post('profile/update', [ProfileController::class, 'update'])->name('admin.profile.update')->can('manage profile');
        Route::get('profile/2fa', [ProfileController::class, 'twofactorauth'])->name('admin.mfa')->can('manage profile');
        Route::post('profile/twofactor/update', [ProfileController::class, 'twofactorUpdate'])->name('admin.twofactor.update')->can('manage profile');
        Route::get('profile/twofactor/disable', [ProfileController::class, 'twofactorDisable'])->name('admin.twofactor.disable')->can('manage profile');
        Route::post('admin-authenticate', [ProfileController::class, 'authenticate'])->name('admin.authenticate');
        //plans
        Route::get('/plans', [PlansController::class, 'index'])->name('admin.plans')->can('manage plans');
        Route::get('/plans/create', [PlansController::class, 'create'])->name('admin.plans.create')->can('create plans');
        Route::post('/plans/store', [PlansController::class, 'store'])->name('admin.plans.store')->can('create plans');
        Route::get('plans/{plan}/edit', [PlansController::class, 'edit'])->name('admin.plans.edit')->can('edit plans');
        Route::post('plans/{plan}/update', [PlansController::class, 'update'])->name('admin.plans.update')->can('edit plans');
        Route::delete('plans/{plan}', [PlansController::class, 'destroy'])->name('admin.plans.destroy')->can('delete plans');
        Route::get('plans/status/change/{id}/{status}', [PlansController::class, 'statusChange'])->name('admin.plans.status.change')->can('manage plans');
        Route::get('/newplan', [PlansController::class, 'createPlanSusbcription'])->name('admin.createPlanSusbcription')->can('manage plans');
        Route::get('plans/transactions/bank-transfer', [PlansController::class, 'bankTransfer'])->name('admin.transactions.bankTransfer')->can('manage transactions');
        Route::get('banktransfer/status/change/{id}/{status}', [PlansController::class, 'banktransferStatusChange'])->name('admin.banktransfer.status.change')->can('manage plans');

/*
        //adds
        Route::get('/advertisements', [AdvertisementsController::class, 'index'])->name('admin.advertisements')->can('manage advertisements');
        Route::get('/advertisements/create/{type}', [AdvertisementsController::class, 'create'])->name('admin.advertisements.create')->can('create advertisements');
        Route::post('/advertisements/store', [AdvertisementsController::class, 'store'])->name('admin.advertisements.store')->can('create advertisements');
        Route::get('advertisements/{advertisement}/edit', [AdvertisementsController::class, 'edit'])->name('admin.advertisements.edit')->can('edit advertisements');
        Route::post('advertisements/{advertisement}/update', [AdvertisementsController::class, 'update'])->name('admin.advertisements.update')->can('edit advertisements');
        Route::get('advertisements/status/change/{id}/{status}', [AdvertisementsController::class, 'statusChange'])->name('admin.advertisements.status.change')->can('manage advertisements');
        Route::delete('advertisements/{advertisement}', [AdvertisementsController::class, 'destroy'])->name('admin.advertisements.destroy')->can('delete advertisements');
*/

        // faqs admin
        Route::get('faq', [FaqController::class, 'index'])->name('admin.faqs.index')->can('manage faqs');
        Route::get('faq/create', [FaqController::class, 'create'])->name('admin.faqs.create')->can('create faqs');
        Route::post('faq', [FaqController::class, 'store'])->name('admin.faqs.store')->can('create faqs');
        Route::get('faq/change-status/{faq}/{status}', [FaqController::class, 'changeStatus'])->name('admin.faqs.changeStatus')->can('edit faqs');
        Route::get('faq/edit/{faq}', [FaqController::class, 'edit'])->name('admin.faqs.edit')->can('edit faqs');
        Route::post('faq/{faq}', [FaqController::class, 'update'])->name('admin.faqs.update')->can('edit faqs');
        Route::delete('faq/destroy/{faq}', [FaqController::class, 'destroy'])->name('admin.faqs.destroy')->can('delete faqs');

        //Widgets Routes
        Route::resource('/widgets', WidgetsController::class, ['as' => 'admin', 'only' => ['index', 'store', 'update', 'destroy']]);
        Route::post('/widgets/sort', [WidgetsController::class, 'sort'])->name('admin.widgets.sort')->can('manage widgets');
        Route::get('plans/transactions', [PlansController::class, 'transactions'])->name('admin.subscriptions')->can('manage transactions');

        // Update routes
        Route::get('/check-updates', [UpdateController::class, 'checkUpdates'])->name('update.checkUpdates')->can('manage updates');
        Route::get('/verify-updates', [UpdateController::class, 'verifyUpdates'])->name('update.verifyUpdates')->can('manage updates');

        // System tools
        Route::get('/rebuild', [SystemController::class, 'rebuild'])->name('system.rebuild')->can('application operations');
        Route::get('/optimize', [SystemController::class, 'optimize'])->name('system.optimize')->can('application operations');
        Route::get('/clear-cache', [SystemController::class, 'cache'])->name('system.cache')->can('application operations');
        Route::get('/clear-view-cache', [SystemController::class, 'view'])->name('system.view')->can('application operations');
        Route::get('/clear-route-cache', [SystemController::class, 'route'])->name('system.route')->can('application operations');
        Route::get('/clean-temp', [SystemController::class, 'cleanTemp'])->name('system.clean-temp')->can('application operations');
        Route::get('/sitemap', [SitemapController::class, 'generate'])->name('sitemap.generate')->can('application operations');
        Route::get('/update', [UpdateController::class, 'show'])->name('system.update')->can('application operations');
        Route::post('/update', [UpdateController::class, 'update'])->name('system.update.run')->can('application operations');

        Route::get('/patches', [UpdateController::class, 'patches'])->name('system.patches')->can('application operations');
        Route::post('/patches/{id}', [UpdateController::class, 'applyPatches'])->name('system.patches.apply')->can('application operations');

        Route::get('/themes', [ThemesController::class, 'index'])->name('admin.themes.manage')->can('application operations');
        Route::post('/themes/install', [ThemesController::class, 'install'])->name('admin.theme.install')->can('application operations');
        Route::get('/themes/activate/{theme}', [ThemesController::class, 'activate'])->name('admin.themes.activate')->can('application operations');

        // Feedback
/*
        Route::get('/feedback', [AdminFeedbackController::class, 'index'])->name('admin.feedback.index');
        Route::get('/feedback/{feedback}', [AdminFeedbackController::class, 'show'])->name('admin.feedback.show');
        Route::post('/feedback/{feedback}/status', [AdminFeedbackController::class, 'updateStatus'])->name('admin.feedback.status');
        Route::delete('/feedback/{feedback}', [AdminFeedbackController::class, 'destroy'])->name('admin.feedback.destroy');
*/
    }
);
