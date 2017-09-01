<?php
return [
    
    'label' => [

        // ============================================= GLOBAL LABELS
        'global' => 'عمومی',
        'visible' => 'قابل مشاهده',
        'active' => 'فعال',
        'title' => 'عنوان',
        'code' => 'کد',
        'type' => 'نوع',
        'id' => 'شناسه',
        'email' => 'ایمیل',
        'cellphone' => 'شماره موبایل',
        'description' => 'توضیحات',
        'comment' => 'توضیحات',
        'dashboard' => 'داشبورد',
        'any' => 'همه',
        'yes' => 'بله',
        'no' => 'خیر',
        'here' => 'اینجا',
        'actions' => 'عملیات',

        // ============================================= BUTTONS
        'delete' => 'حذف',
        'update' => 'به روزرسانی',
        'edit' => 'ویرایش',
        'submit' => 'ثبت',
        'search' => 'جستجو ...',
        'view' => 'مشاهده',
        'close' => 'ببند',

        // ============================================= AUTHENTICATIONS
        'username' => 'نام کاربری',
        'password' => 'کلمه عبور',
        'password_confirmed' => 'تکرار کلمه عبور',
        'forget_password' => 'فراموشی رمز عبور',
        'change_password' => 'تغییر کلمه عبور',
        'remember_me' => 'به خاطر بسپار',
        'my_profile' => 'پروفایل من',
        'logout' => 'خروج',
        'login' => 'ورود',

        // ============================================= USERS
        'users' => 'کاربران',
        'user' => [
            'new' => 'کاربر جدید',
            'list' => 'لیست کاربران',
            'edit' => 'ویرایش کاربر',
            'management' => 'مدیریت کاربران',
            
            'data' => 'اطلاعات کاربری',
            'firstname' => 'نام',
            'lastname' => 'نام خانوادگی',
            'fullname' => 'نام کامل',
            'birthday' => 'تاریخ تولد',
            'name' => 'نام کاربر',
            'national_code' => 'کد ملی',
            'type' => 'نوع کاربر',
            'lastseen' => 'آخرین فعالیت',
            'register_date' => 'تاریخ عضویت',
            'sex' => [
                'label' => 'جنسیت',
                'male' => 'مرد',
                'female' => 'زن',
            ],
            
            'status' => [
                'label' => 'وضعیت کاربر',
                'active' => 'فعال',
                'deactive' => 'غیرفعال',
                'locked' => 'قفل شده',
            ],
        ],
        'locale' => [
            'label' => 'زبان',
            'fa' => 'فارسی',
            'en' => 'English',
        ],
        'access_managements' => 'مدیریت دسترسی ها',


        // ============================================= ROLES AND ACTIONS
        'actiongroup' => [
            'new' => 'گروه عملیاتی جدید',
            'list' => 'لیست گروه‌های‌ عملیاتی',
            'edit' => 'ویرایش گروه عملیاتی %name%',
            'management' => 'مدیریت گروه‌های عملیاتی',

            'title' => 'گروه‌های عملیاتی',
            'desc' => 'تعریف دسترسی برای قسمت‌های مختلف نرم‌افزار',
            'code' => 'کد گروه عملیاتی',
        ],
        'roles' => 'نقش‌ها',
        'role' => [
            'label' => 'نقش',
            'new' => 'نقش جدید',
			'list' => 'لیست نقش‌ها',
            'edit' => 'ویرایش نقش %name%',
            'management' => 'مدیریت نقش ها',

            'title' => 'عنوان نقش',
            'super_admin' => 'سوپر ادمین',
            'admin' => 'ادمین',
            'code' => 'کد نقش',
		],

        // ============================================= GEO
        'province' => [
            'title' => 'استان',
            'list' => 'لیست استان ها',
        ],
        'city' => [
            'list' => 'لیست شهر ها',
            'title' => 'شهر',
        ],

        
        // ============================================= COMMUNICATION AND TEMPLATE
         'communication_template' => [
            'new' => 'قالب پیام جدید',
            'list' => 'قالب پیام ها',
            'edit' => 'ویرایش قالب پیام',
            'type' => 'نوع قالب',
        ],

        'communication_job' => [
            'list' => 'صف پیام ها',
        ],

        // ============================================= SYSTEM AND SETTING
        'system' => [
            'settings' => 'تنظیمات',
            'configurations' => 'پیکربندی نرم‌افزار',
            'logs' => 'لاگ‌های نرم‌افزار',
            'analytics' => 'آمار نرم‌افزار',
            'info' => 'اطلاعات نرم‌افزار',
        ],


        // ============================================= DATE AND TIME
        'date' => [
            'update' => 'زمان به روز رسانی',
            'submit' => 'تاریخ ثبت',
            'begin' => 'تاریخ شروع',
            'end' => 'تاریخ پایان',
            'creation' => 'تاریخ ساخت',
        ],

        // ============================================= MESSAGES AND ERRORS
        'password_mismatch' => 'عدم انطباق کلمه عبور',
        'password_is_wrong' => 'رمز عبور اشتباه است',
        'password_successfully_changed' => 'رمز عبور با موفقیت تغییر کرد',
        'password_6characters_at_least' => 'رمز عبور حداقال باید ۶ کاراکتر باشد',
        'incorrect_captcha' => 'کد امنیتی نادرست است',
        'is_required' => 'اجباری است',
        'email_exist' => 'با این ایمیل قبلا ثبت نام شده است.',
        'activation_email_sent' => 'ایمیل فعال سازی ارسال شد!',
        'account_activate_successfuly' => 'اکانت شما با موفقیت فعال شد!',
        'error_in_account_activation' => 'خطا در فعال سازی اکانت!',
        'your_account_already_activated' => 'اکانت شما فعال شده است!',
        'nationalCode_exist' => 'با این کد ملی قبلا ثبت نام شده است',
        'cellphone_exist' => 'با این تلفن قبلا ثبت نام شده است',
        'activation_email_already_sent' => 'ایمیل فعال سازی ارسال شده است!',
        'successfully_updated' => 'با موفقیت به روزرسانی شد',
        'user_pass_empty' => 'نام کاربری و پسورد خالی است',


        'system_data' => 'اطلاعات سیستمی',
        'account_data' => 'اطلاعات اکانت',
        'personal_data' => 'اطلاعات شخصی',
        'basic_data' =>  'اطلاعات پایه',
        'contact_data' => 'اطلاعات ارتباطی',
        'creation_data' => 'اطلاعات ساخت',

        // ============================================= MESSAGES AND ERRORS
        'sensor' => [
            'management' => 'مدیریت سنسورها',
            'map' => 'سنسورها بر روی نقشه',
            'spec' => 'مشخصات سنسور',
            'last_state' => 'آخرین وضعیت سنسور',
            'device_id' => 'شناسه دستگاه',
            'type' => 'نوع دستگاه',
            'brand' => 'سازنده دستگاه',
            'metric_value' => 'آمار کلی سنسور',
            'statistics_on_chart' => 'آمار سنسور بر روی نمودار',
        ],
        'temperature' => 'دما',
        'humidity' => 'رطوبت',
        'weight' => 'وزن',
        'height' => 'ارتفاع',
        'width' => 'عرض',
    ],

];