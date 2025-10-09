# Laravel Cashier Stripe Billing Setup

## ✅ Completed Setup

Your HireCamp Dashboard now has a complete billing system using Laravel Cashier and Stripe!

## 🎯 Features Implemented

### 1. **Subscription Plans**
- **Free**: 5 AI interviews/month, basic features
- **Basic** ($19/mo): 50 AI interviews, advanced analytics, unlimited resumes
- **Pro** ($49/mo): Unlimited AI interviews, AI personas, team features (5 members)
- **Enterprise** ($199/mo): Everything + unlimited team members, custom branding

### 2. **Stripe Integration**
- ✅ Laravel Cashier installed and configured
- ✅ Database migrations run (customers, subscriptions, subscription_items tables)
- ✅ User model updated with `Billable` trait
- ✅ Stripe webhooks configured

### 3. **Billing Portal**
- Beautiful, responsive billing dashboard with DaisyUI/Tailwind
- View all subscription plans
- Switch/upgrade/downgrade plans
- Manage payment methods via Stripe Customer Portal
- View invoice history
- Download invoices
- Cancel/resume subscriptions

### 4. **Payment Flow**
- Stripe Checkout for new subscriptions
- Automatic plan switching for existing subscribers
- Success page after subscription
- Webhook handling for payment events

### 5. **Middleware Protection**
- `subscribed` middleware: Requires active subscription
- `feature` middleware: Checks feature access based on plan
- Plan hierarchy enforcement (Free → Basic → Pro → Enterprise)

## 🔧 Configuration Required

### 1. **Stripe API Keys**

Add these to your `.env` file:

```env
STRIPE_KEY=pk_test_your_stripe_publishable_key
STRIPE_SECRET=sk_test_your_stripe_secret_key
STRIPE_WEBHOOK_SECRET=whsec_your_webhook_secret

CASHIER_CURRENCY=usd
CASHIER_CURRENCY_LOCALE=en_US
```

### 2. **Create Stripe Products & Prices**

1. Log in to your [Stripe Dashboard](https://dashboard.stripe.com/)
2. Go to **Products** → **Add Product**
3. Create products for each plan (Basic, Pro, Enterprise)
4. For each product, create a **recurring price** (monthly)
5. Copy the Price IDs (e.g., `price_1234567890`)

### 3. **Add Price IDs to .env**

```env
STRIPE_PRICE_FREE=price_free_plan_id
STRIPE_PRICE_BASIC=price_1234567890abc
STRIPE_PRICE_PRO=price_0987654321xyz
STRIPE_PRICE_ENTERPRISE=price_abcdef123456
```

### 4. **Configure Stripe Webhooks**

1. Go to **Developers** → **Webhooks** in Stripe Dashboard
2. Click **Add endpoint**
3. Set the endpoint URL: `https://your-domain.com/stripe/webhook`
4. Select events to listen to:
   - `customer.subscription.created`
   - `customer.subscription.updated`
   - `customer.subscription.deleted`
   - `invoice.payment_succeeded`
   - `invoice.payment_failed`
5. Copy the **Webhook signing secret** to your `.env` as `STRIPE_WEBHOOK_SECRET`

## 📂 Files Created

### Models & Config
- `app/Models/User.php` - Added `Billable` trait
- `config/plans.php` - Subscription plans configuration
- `config/cashier.php` - Cashier configuration

### Controllers
- `app/Http/Controllers/CheckoutController.php` - Stripe Checkout
- `app/Http/Controllers/WebhookController.php` - Webhook handling

### Livewire Components
- `app/Livewire/BillingManager.php` - Main billing component

### Views
- `resources/views/livewire/billing-manager.blade.php` - Billing dashboard
- `resources/views/billing/success.blade.php` - Success page

### Middleware
- `app/Http/Middleware/EnsureUserIsSubscribed.php` - Subscription check
- `app/Http/Middleware/CheckFeatureAccess.php` - Feature access control

### Routes
- `/billing` - Billing dashboard
- `/billing/checkout/{plan}` - Stripe Checkout
- `/billing/success` - Success page
- `/stripe/webhook` - Webhook endpoint

## 🎨 Usage Examples

### Protect Routes with Subscription

```php
// Require any active subscription
Route::get('/premium-feature', function () {
    // ...
})->middleware(['auth', 'subscribed']);

// Require specific plan or higher
Route::get('/pro-feature', function () {
    // ...
})->middleware(['auth', 'subscribed:pro']);

// Require specific feature access
Route::get('/ai-personas', function () {
    // ...
})->middleware(['auth', 'feature:ai_personas']);
```

### Check Subscription in Controllers

```php
use Illuminate\Support\Facades\Auth;

// Check if user has any subscription
if (Auth::user()->subscribed('default')) {
    // User is subscribed
}

// Check if user is on specific plan
if (Auth::user()->onPlan('pro')) {
    // User is on Pro plan
}

// Get subscription
$subscription = Auth::user()->subscription('default');
```

### Check Subscription in Blade

```blade
@if(auth()->user()->subscribed('default'))
    <!-- Show premium content -->
@else
    <a href="{{ route('billing.index') }}">Upgrade to Premium</a>
@endif
```

## 🚀 Testing

### Test Mode (Stripe Test Keys)

Use Stripe's test card numbers:
- Success: `4242 4242 4242 4242`
- Decline: `4000 0000 0000 0002`
- 3D Secure: `4000 0025 0000 3155`

Any future expiry date and any 3-digit CVC will work.

### Test Webhooks Locally

Use Stripe CLI to forward webhooks:

```bash
stripe listen --forward-to localhost:8000/stripe/webhook
```

## 📊 Monitoring

- **Stripe Dashboard**: Monitor payments, subscriptions, customers
- **Laravel Logs**: `storage/logs/laravel.log` for webhook events
- **Database**: `subscriptions` table for all subscription data

## 🎉 Next Steps

1. **Set up Stripe account** (if not done)
2. **Add API keys** to `.env`
3. **Create products** in Stripe Dashboard
4. **Configure webhooks** in Stripe
5. **Test subscriptions** with test cards
6. **Go live** when ready!

## 💡 Additional Features You Can Add

- **Annual billing** (with discount)
- **Free trials** (14-day trial)
- **Metered billing** (pay-per-use)
- **Usage limits** enforcement
- **Team billing** (charge per member)
- **Coupons/discounts**
- **Referral program**

## 🆘 Support

- [Laravel Cashier Docs](https://laravel.com/docs/billing)
- [Stripe API Docs](https://stripe.com/docs/api)
- [Stripe Dashboard](https://dashboard.stripe.com/)

---

**Built with Laravel Cashier, Stripe, and Livewire** 🚀

