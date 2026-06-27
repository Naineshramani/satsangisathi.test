<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckMemberPackage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && $user->member && is_null($user->member->current_package_id)) {

            if (!$request->routeIs([
                'home', 'packages', 'package_payment_methods', 'free_package_purchase',
                'package_payment.invoice', 'package_purchase_history', 'package.payment',
                'profile_settings', 'dashboard', 'user.logout',
                'member.introduction.update', 'member.basic_info.update',
                'member.contact_info.update', 'member.partner_preference.update',
                'member.physical_attribute.update', 'member.religious_info.update',
                'member.professional_info.update', 'member.family_info.update',
                'member.horoscope_info.update', 'member.present_address.update',
                'member.permanent_address.update', 'member.change_password',
                'member.account_deactivation', 'member.account_delete',
                'user.change.email', 'verification.resend',
                'gallery-image_index', 'gallery-image.store', 'gallery-image.destroy',
            ])) {
                flash(translate('Please purchase a package first.'))->warning();
                return redirect()->route('packages');
            }
        }

        return $next($request);
    }
}
