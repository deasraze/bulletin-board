 <?php

 use App\Entity\Adverts\Advert\Advert;
 use App\Entity\Banner\Banner;
 use App\Entity\Page;
 use App\Entity\Region;
 use App\Entity\User\User;
 use App\Entity\Adverts\Category;
 use App\Entity\Adverts\Attribute;
 use App\Http\Router\AdvertsPath;
 use App\Http\Router\PagePath;

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

 /* Page */
 // Home > page->title
 Breadcrumbs::for('page', function ($trail, PagePath $path) {
     ($parent = $path->page->parent)
         ? $trail->parent('page', $path->withPage($parent))
         : $trail->parent('home');
     $trail->push($path->page->title, route('page', $path));
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

 // Home > Cabinet > Favorites
 Breadcrumbs::for('cabinet.favorites.index', function ($trail) {
     $trail->parent('cabinet.home');
     $trail->push('Favorites', route('cabinet.favorites.index'));
 });

 /**
  * Cabinet:
  * Adverts
  */
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

 // Home > Adverts > Create > $categories->name > $regions->name|All
 Breadcrumbs::for('cabinet.adverts.create.advert', function ($trail, Category $category, Region $region = null) {
     $trail->parent('cabinet.adverts.create.region', $category, $region);
     $trail->push($region ? $region->name : 'All', route('cabinet.adverts.create.advert', [$category, $region]));
 });

 // Home > Cabinet > Adverts > $advert->title > Edit
 Breadcrumbs::for('cabinet.adverts.edit', function ($trail, Advert $advert) {
     $trail->parent('cabinet.adverts.index');
     $trail->push($advert->title, route('adverts.show', $advert));
     $trail->push('Edit');
 });

 /**
  * Cabinet:
  * Banners
  */
 // Home > Cabinet > Banners
 Breadcrumbs::for('cabinet.banners.index', function ($trail) {
     $trail->parent('cabinet.home');
     $trail->push('Banners', route('cabinet.banners.index'));
 });

 // Home > Cabinet > Banners > $banner->name
 Breadcrumbs::for('cabinet.banners.show', function ($trail, Banner $banner) {
     $trail->parent('cabinet.banners.index');
     $trail->push($banner->name, route('cabinet.banners.show', $banner));
 });

 // Home > Cabinet > Banners > $banner->name > Edit
 Breadcrumbs::for('cabinet.banners.edit', function ($trail, Banner $banner) {
     $trail->parent('cabinet.banners.show', $banner);
     $trail->push('Edit', route('cabinet.banners.edit', $banner));
 });

 // Home > Cabinet > Banners > $banner->name > File
 Breadcrumbs::for('cabinet.banners.file', function ($trail, Banner $banner) {
     $trail->parent('cabinet.banners.show', $banner);
     $trail->push('File', route('cabinet.banners.file', $banner));
 });

 // Home > Cabinet > Banners > Create
 Breadcrumbs::for('cabinet.banners.create', function ($trail) {
     $trail->parent('cabinet.banners.index');
     $trail->push('Create', route('cabinet.banners.create'));
 });

 // Home > Cabinet > Banners > Create > $category->name
 Breadcrumbs::for('cabinet.banners.create.region', function ($trail, Category $category, Region $region = null) {
     $trail->parent('cabinet.banners.create');
     $trail->push($category->name, route('cabinet.banners.create.region', [$category, $region]));
 });

 // Home > Cabinet > Banners > Create > $categories->name > $regions->name|All
 Breadcrumbs::for('cabinet.banners.create.banner', function ($trail, Category $category, Region $region = null) {
     $trail->parent('cabinet.banners.create.region', $category, $region);
     $trail->push($region ? $region->name : 'All', route('cabinet.banners.create.banner', [$category, $region]));
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

 // Home > Admin > Adverts > $advert->title
 Breadcrumbs::for('admin.adverts.inner_advert', function ($trail, Advert $advert) {
     $trail->parent('admin.adverts.adverts.index');
     $trail->push($advert->title, route('adverts.show', $advert));
 });

 // Home > Admin > Adverts > $advert->title > Edit
 Breadcrumbs::for('admin.adverts.adverts.edit', function ($trail, Advert $advert) {
     $trail->parent('admin.adverts.inner_advert', $advert);
     $trail->push('Edit', route('admin.adverts.adverts.edit', $advert));
 });

 // Home > Admin > Adverts > $advert->title > Reject
 Breadcrumbs::for('admin.adverts.adverts.reject', function ($trail, Advert $advert) {
     $trail->parent('admin.adverts.inner_advert', $advert);
     $trail->push('Reject', route('admin.adverts.adverts.reject', $advert));
 });

 /**
  * Admin Panel:
  * Pages
  */
 // Home > Admin > Pages
 Breadcrumbs::for('admin.pages.index', function ($trail) {
     $trail->parent('admin.home');
     $trail->push('Pages', route('admin.pages.index'));
 });

 // Home > Admin > Pages > Create
 Breadcrumbs::for('admin.pages.create', function ($trail) {
     $trail->parent('admin.pages.index');
     $trail->push('Create', route('admin.pages.create'));
 });

 // Home > Admin > Pages > $page->title
 Breadcrumbs::for('admin.pages.show', function ($trail, Page $page) {
     ($parent = $page->parent)
         ? $trail->parent('admin.pages.show', $parent)
         : $trail->parent('admin.pages.index');
     $trail->push($page->title, route('admin.pages.show', $page));
 });

 // Home > Admin > Pages > $page->title > Edit
 Breadcrumbs::for('admin.pages.edit', function ($trail, Page $page) {
     $trail->parent('admin.pages.show', $page);
     $trail->push('Edit', route('admin.pages.edit', $page));
 });

 /**
  * Admin Panel:
  * Banners
  */
 // Home > Admin > Banners
 Breadcrumbs::for('admin.banners.index', function ($trail) {
     $trail->parent('admin.home');
     $trail->push('Banners', route('admin.banners.index'));
 });

 // Home > Admin > Banners > $banner->name
 Breadcrumbs::for('admin.banners.show', function ($trail, Banner $banner) {
     $trail->parent('admin.banners.index', $banner);
     $trail->push($banner->name, route('admin.banners.show', $banner));
 });

 // Home > Admin > Banners > $banner->name > Edit
 Breadcrumbs::for('admin.banners.edit', function ($trail, Banner $banner) {
     $trail->parent('admin.banners.show', $banner);
     $trail->push('Edit', route('admin.banners.edit', $banner));
 });

 // Home > Admin > Banners > $banner->name > Reject
 Breadcrumbs::for('admin.banners.reject', function ($trail, Banner $banner) {
     $trail->parent('admin.banners.show', $banner);
     $trail->push('Reject', route('admin.banners.reject', $banner));
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
