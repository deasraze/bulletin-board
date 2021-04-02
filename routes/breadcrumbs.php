 <?php

 use App\Entity\Region;
 use App\Entity\User;

 /* Site */
 // Home
 Breadcrumbs::for('home', function ($trail) {
     $trail->push('Home', route('home'));
 });

 // Home  > Login
 Breadcrumbs::for('login', function ($trail) {
     $trail->parent('home');
     $trail->push('Login', route('login'));
 });

 // Home > for
 Breadcrumbs::for('for', function ($trail) {
     $trail->parent('home');
     $trail->push('for', route('for'));
 });

 // Home > Login > Reset Password
 Breadcrumbs::for('password.request', function ($trail) {
     $trail->parent('login');
     $trail->push('Reset Password', route('password.request'));
 });

 // Home > Login > Reset Password > Change
 Breadcrumbs::for('password.reset', function ($trail) {
     $trail->parent('password.request');
     $trail->push('Change', route('password.reset'));
 });

 // Home > Cabinet
 Breadcrumbs::for('cabinet', function ($trail) {
     $trail->parent('home');
     $trail->push('Cabinet', route('cabinet'));
 });

 /* Admin Panel */
 // Home > Admin
 Breadcrumbs::for('admin.home', function ($trail) {
     $trail->parent('home');
     $trail->push('Admin', route('admin.home'));
 });

/**
 * Admin Panel:
 * Users
 */
 // Home > Admin > Users
 Breadcrumbs::for('admin.users.index', function ($trail) {
     $trail->parent('admin.home');
     $trail->push('Users', route('admin.users.index'));
 });

 // Home > Admin > Users > Create
 Breadcrumbs::for('admin.users.create', function ($trail) {
     $trail->parent('admin.users.index');
     $trail->push('Create', route('admin.users.create'));
 });

 // Home > Admin > Users > $user->name
 Breadcrumbs::for('admin.users.show', function ($trail, User $user) {
     $trail->parent('admin.users.index');
     $trail->push($user->name, route('admin.users.show', $user));
 });

 // Home > Admin > Users > $user->name > Edit
 Breadcrumbs::for('admin.users.edit', function ($trail, User $user) {
     $trail->parent('admin.users.show', $user);
     $trail->push('Edit', route('admin.users.edit', $user));
 });

 /**
  * Admin Panel:
  * Regions
  */
 // Home > Admin > Regions
 Breadcrumbs::for('admin.regions.index', function ($trail) {
     $trail->parent('admin.home');
     $trail->push('Regions', route('admin.regions.index'));
 });

 // Home > Admin > Regions > Create
 Breadcrumbs::for('admin.regions.create', function ($trail) {
     $trail->parent('admin.regions.index');
     $trail->push('Create', route('admin.regions.create'));
 });

 // Home > Admin > Regions > $region->name
 Breadcrumbs::for('admin.regions.show', function ($trail, Region $region) {
     ($parent = $region->parent)
         ? $trail->parent('admin.regions.show', $parent)
         : $trail->parent('admin.regions.index');
     $trail->push($region->name, route('admin.regions.show', $region));
 });

 Breadcrumbs::for('admin.regions.edit', function ($trail, Region $region) {
     $trail->parent('admin.regions.show', $region);
     $trail->push('Edit', route('admin.regions.edit', $region));
 });
