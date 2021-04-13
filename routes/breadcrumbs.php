 <?php

 use App\Entity\Adverts\Advert\Advert;
 use App\Entity\Region;
 use App\Entity\User;
 use App\Entity\Adverts\Category;
 use App\Entity\Adverts\Attribute;
 use App\Http\Router\AdvertsPath;

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

 // Home  > Login
 Breadcrumbs::for('login.phone', function ($trail) {
     $trail->parent('home');
     $trail->push('Login', route('login.phone'));
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

 /* Adverts */
 Breadcrumbs::for('adverts.inner_region', function ($trail, AdvertsPath $path) {
     if ($path->region && $parent = $path->region->parent) {
         $trail->parent('adverts.inner_region', $path->withRegion($parent));
     } else {
         $trail->parent('home');
         $trail->push('Adverts', route('adverts.index'));
     }

     if ($path->region) {
         $trail->push($path->region->name, route('adverts.index', $path));
     }
 });

 Breadcrumbs::for('adverts.inner_category', function ($trail, AdvertsPath $path, AdvertsPath $orig) {
     ($path->category && $parent = $path->category->parent)
         ? $trail->parent('adverts.inner_category', $path->withCategory($parent), $orig)
         : $trail->parent('adverts.inner_region', $orig);

     if ($path->category) {
         $trail->push($path->category->name, route('adverts.index', $path));
     }
 });

 // Home > Adverts > $regions->name > $categories->name
 Breadcrumbs::for('adverts.index', function ($trail, AdvertsPath $path = null) {
     $path = $path ?: adverts_path(null, null);
     $trail->parent('adverts.inner_category', $path, $path);
 });

 // Home > Adverts > $regions->name > $categories->name > $advert->title
 Breadcrumbs::for('adverts.show', function ($trail, Advert $advert) {
     $trail->parent('adverts.index', adverts_path($advert->region, $advert->category));
     $trail->push($advert->title, route('adverts.show', $advert));
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

 // Home > Cabinet > Profile > Phone
 Breadcrumbs::for('cabinet.profile.phone', function ($trail) {
     $trail->parent('cabinet.profile.home');
     $trail->push('Phone', route('cabinet.profile.phone'));
 });

 // Home > Cabinet > Adverts
 Breadcrumbs::for('cabinet.adverts.index', function ($trail) {
     $trail->parent('cabinet.home');
     $trail->push('Adverts', route('cabinet.adverts.index'));
 });

 // Home > Adverts > Create
 Breadcrumbs::for('cabinet.adverts.create', function ($trail) {
     $trail->parent('adverts.index');
     $trail->push('Create', route('cabinet.adverts.create'));
 });

 // Home > Adverts > Create > $categories->name
 Breadcrumbs::for('cabinet.adverts.create.region', function ($trail, Category $category, Region $region = null) {
     $trail->parent('cabinet.adverts.create');
     $trail->push($category->name, route('cabinet.adverts.create.region', [$category, $region]));
 });

 // Home > Adverts > Create > $categories->name  > $regions->name|All
 Breadcrumbs::for('cabinet.adverts.create.advert', function ($trail, Category $category, Region $region = null) {
     $trail->parent('cabinet.adverts.create.region', $category, $region);
     $trail->push($region ? $region->name : 'All', route('cabinet.adverts.create.advert', [$category, $region]));
 });

 /* Admin Panel */
 // Home > Admin
 Breadcrumbs::for('admin.home', function ($trail) {
     $trail->parent('home');
     $trail->push('Admin', route('admin.home'));
 });

 /**
  * Admin Panel:
  * Adverts
  */
 // Home > Admin > Adverts
 Breadcrumbs::for('admin.adverts.adverts.index', function ($trail) {
     $trail->parent('admin.home');
     $trail->push('Adverts', route('admin.adverts.adverts.index'));
 });

 // Home > Admin > $advert->title
 Breadcrumbs::for('admin.adverts.adverts.edit', function ($trail, Advert $advert) {
     $trail->parent('admin.home');
     $trail->push($advert->title, route('admin.adverts.adverts.edit', $advert));
 });

 // Home > Admin > $advert->title
 Breadcrumbs::for('admin.adverts.adverts.reject', function ($trail, Advert $advert) {
     $trail->parent('admin.adverts.adverts.edit', $advert);
     $trail->push('Reject', route('admin.adverts.adverts.reject', $advert));
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
