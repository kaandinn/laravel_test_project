<?php

namespace App\Providers;

use App\Models\Ticket;
use App\Models\User;
use App\Models\Comment;
use App\Policies\TicketPolicy;
use App\Enums\UserRoleEnum;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        Ticket::class => TicketPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('owner_or_admin_ticket_permission', function(User $user, Ticket $ticket){
            return ($ticket->user_id !== $user->id) and ($user->role !== UserRoleEnum::ADMIN);
        });

        Gate::define('is_admin_permission', function(User $user){
            return $user->role === UserRoleEnum::ADMIN;
        });

        Gate::define('is_not_admin_permission', function(User $user){
            return $user->role !== UserRoleEnum::ADMIN;
        });

        Gate::define('owner_or_admin_comment_permission', function(User $user, Comment $comment){
            return ($comment->user_id !== $user->id) and ($user->role !== UserRoleEnum::ADMIN);
        });

        Gate::define('is_not_owner_permission', function(User $user, Comment $comment){
            return $comment->user_id !== $user->id;
        });

        Gate::define('protected-post-comment', function(User $user, $request) {
            $tickets = User::find($user->id)->tickets()->where('id', '=', $request->ticket_id)->first();
            return !empty($tickets) and ($tickets->id == $request->ticket_id);
        });
    }
}
