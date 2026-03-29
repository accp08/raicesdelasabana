<?php

namespace App\Providers;

use App\Models\AboutPage;
use App\Models\Post;
use App\Models\Category;
use App\Models\City;
use App\Models\Property;
use App\Models\PropertyLead;
use App\Models\User;
use App\Policies\AboutPagePolicy;
use App\Policies\CityPolicy;
use App\Policies\PropertyLeadPolicy;
use App\Policies\PostPolicy;
use App\Policies\CategoryPolicy;
use App\Policies\PropertyPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        User::class => UserPolicy::class,
        Property::class => PropertyPolicy::class,
        Post::class => PostPolicy::class,
        Category::class => CategoryPolicy::class,
        AboutPage::class => AboutPagePolicy::class,
        PropertyLead::class => PropertyLeadPolicy::class,
        City::class => CityPolicy::class,
    ];

    public function boot(): void
    {
        // Policies auto-discovered by mapping above.
    }
}
