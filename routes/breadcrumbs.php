 <?php

 use App\Entity\Region;
 use App\Entity\User;
 use App\Entity\Adverts\Category;
 use App\Entity\Adverts\Attribute;

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

 // Home > Register
 Breadcrumbs::for('register', function ($trail) {
     $trail->parent('home');
     $trail->push('Register', route('register'));
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

 /* Cabinet */
 // Home > Cabinet
 Breadcrumbs::for('cabinet.home', function ($trail) {
     $trail->parent('home');
     $trail->push('Cabinet', route('cabinet.home'));
 });

 // Home > Cabinet > Profile
 Breadcrumbs::for('cabinet.profile.home', function ($trail) {
     $trail->parent('cabinet.home');
     $trail->push('Profile', route('cabinet.profile.home'));
 });

 // Home > Cabinet > Profile > Edit
 Breadcrumbs::for('cabinet.profile.edit', function ($trail) {
     $trail->parent('cabinet.profile.home');
     $trail->push('Edit', route('cabinet.profile.edit'));
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

 // Home > Admin > Regions > $region->name > Edit
 Breadcrumbs::for('admin.regions.edit', function ($trail, Region $region) {
     $trail->parent('admin.regions.show', $region);
     $trail->push('Edit', route('admin.regions.edit', $region));
 });

 /**
  * Admin Panel:
  * Advert Categories
  */
 // Home > Admin > Categories
 Breadcrumbs::for('admin.adverts.categories.index', function ($trail) {
     $trail->parent('admin.home');
     $trail->push('Categories', route('admin.adverts.categories.index'));
 });

 // Home > Admin > Categories > Create
 Breadcrumbs::for('admin.adverts.categories.create', function ($trail) {
     $trail->parent('admin.adverts.categories.index');
     $trail->push('Create', route('admin.adverts.categories.create'));
 });

 // Home > Admin > Categories > $category->name
 Breadcrumbs::for('admin.adverts.categories.show', function ($trail, Category $category) {
     ($parent = $category->parent)
         ? $trail->parent('admin.adverts.categories.show', $parent)
         : $trail->parent('admin.adverts.categories.index');
     $trail->push($category->name, route('admin.adverts.categories.show', $category));
 });

 // Home > Admin > Categories > $category->name > Edit
 Breadcrumbs::for('admin.adverts.categories.edit', function ($trail, Category $category) {
     $trail->parent('admin.adverts.categories.show', $category);
     $trail->push('Edit', route('admin.adverts.categories.edit', $category));
 });

 /**
  * Admin Panel:
  * Advert Category Attributes
  */

 // Home > Admin > Categories > $category->name > $attribute->name
 Breadcrumbs::for('admin.adverts.categories.attributes.show', function ($trail, Category $category, Attribute $attribute) {
     $trail->parent('admin.adverts.categories.show', $category);
     $trail->push($attribute->name, route('admin.adverts.categories.attributes.show', [$category, $attribute]));
 });

 // Home > Admin > Categories > $category->name > $attribute->name > Edit
 Breadcrumbs::for('admin.adverts.categories.attributes.edit', function ($trail, Category $category, Attribute $attribute) {
     $trail->parent('admin.adverts.categories.attributes.show', $category, $attribute);
     $trail->push('Edit', route('admin.adverts.categories.attributes.edit', [$category, $attribute]));
 });

 // Home > Admin > Categories > $category->name > Create
 Breadcrumbs::for('admin.adverts.categories.attributes.create', function ($trail, Category $category) {
     $trail->parent('admin.adverts.categories.show', $category);
     $trail->push('Create', route('admin.adverts.categories.attributes.create', $category));
 });
