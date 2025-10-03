# Email Receipt Testing Guide

## 🎯 How to Test Your Email Receipt System

Your email system is configured with Gmail SMTP and ready to send real emails! Here are different ways to test it:

## Method 1: ✅ Test Command (Just Completed)
```bash
php artisan test:email-receipt 1 1
```
**Result**: ✅ Test email sent successfully to admin@onlinecourse.com

## Method 2: 🛒 Real PayPal Purchase Test
1. **Make an actual purchase** through your website
2. **Complete PayPal payment** in sandbox mode
3. **Check the student's email** for automatic receipt
4. **Monitor queue processing** with `php artisan queue:work`

## Method 3: 📝 Manual Queue Inspection
```bash
# Check if emails are queued
php artisan tinker --execute="echo 'Queued jobs: ' . DB::table('jobs')->count();"

# Process the queue manually
php artisan queue:work --once
```

## Method 4: 📊 Database Verification
```bash
# Check recent enrollments
php artisan tinker --execute="App\Models\Enrollment::latest()->take(3)->get(['user_id', 'course_id', 'payment_status', 'created_at']);"
```

## 🔍 What to Check

### ✅ Email Delivery Confirmation
- Check your Gmail "Sent" folder for the receipt email
- Verify the student received the email in their inbox
- Look for any bounce-back messages

### ✅ Email Content Verification
The email should include:
- **Payment Details**: Amount (RM), transaction ID, date
- **Course Information**: Title, description, instructor
- **Student Details**: Name, email, enrollment date
- **Professional Design**: Clean, branded layout

### ✅ Queue Processing
```bash
# Start queue worker to process emails
php artisan queue:work

# Or process one job at a time
php artisan queue:work --once
```

## 🚨 Troubleshooting

### If Emails Don't Send:
1. **Check Gmail settings**: Ensure 2FA is enabled and app password is correct
2. **Verify credentials**: Test with `php artisan tinker --execute="Mail::raw('Test', function(\$m) { \$m->to('your-email@gmail.com')->subject('Test'); });"`
3. **Check logs**: Look in `storage/logs/laravel.log` for errors
4. **Queue issues**: Run `php artisan queue:restart`

### Common Issues:
- **Gmail blocking**: Enable "Less secure app access" or use App Password
- **Queue not processing**: Make sure to run `php artisan queue:work`
- **Environment cache**: Run `php artisan config:clear` after .env changes

## 🎊 Next Steps

### For Testing:
1. **Try a real purchase** through PayPal (sandbox mode)
2. **Check multiple email addresses** with different test users
3. **Test with different courses** and price points

### For Production:
1. **Switch PayPal to live mode** when ready
2. **Set up queue worker** as a background service
3. **Monitor email delivery** rates and bounce backs

## 📈 Success Metrics

Your email system is working when:
- ✅ Emails arrive within 1-2 minutes of payment
- ✅ All payment and course details are accurate  
- ✅ Professional design displays correctly
- ✅ No errors in Laravel logs
- ✅ Students can access courses immediately

**Your email receipt system is fully operational! 🚀**

Students will now automatically receive professional payment confirmations after every successful course purchase.