<?php

namespace Database\Seeders;

use App\Enums\Status;
use App\Models\Page;
use Dipokhalder\EnvEditor\EnvEditor;
use Illuminate\Database\Seeder;

class PageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $envService = new EnvEditor();
        if ($envService->getValue('DEMO')) {
            Page::insert([
                [
                    'title'           => 'Cookies Policy',
                    'slug'            => 'cookies-policy',
                    'description'     => "This Cookies Policy explains how FoodKing (“us”, “we” or “our”) collects, uses, stores, shares and uses cookies on its website. We provide information on how cookies and similar technologies work, their purpose, use, and duration, how We process and store them, and how you can control them. You can also find all the necessary information regarding your rights on the use of Cookies on this website and how you can exercise them.

COOKIES AND SIMILAR TECHNOLOGIES

Cookies are small files of data that are stored on a user’s device by the browser when visiting a website or application. Cookies are used for various reasons and can be very useful in many cases. They can be necessary for the proper functionality of the website and efficient page navigation, or they can be used to customize and enhance the user’s experience by remembering language preferences, login credentials, and many more. They can also be used to provide anonymous statistical data about the usage of the website or targeted advertisements to the visitor.

Some sites might also use other similar technologies like web beacons, clear GIFs, page tags, and web bugs to understand how people are using them and to target advertising to them. They usually take the form of a small, transparent image that is embedded in a web page or email. They work with cookies and capture data like your IP address, when you viewed the page or email, what device you were using and where you were.

TYPES OF COOKIES

Cookies can be classified as First-party Cookies and Third-Party cookies.

First-party Cookies refer to cookies that are set by a data controller (or any of its processors) operating the website visited by the user, as defined by the URL that is usually displayed in the browser address bar.

Third-party Cookies are set by data controllers that do not operate the website currently visited by the user. For example, if a user visits a website and another entity sets a cookie on the user’s device, through that website, this would be a third-party cookie. For these types of cookies, we have no control over them.


COOKIES CATEGORIES

Cookies are usually categorized into one of the following categories, depending on their purpose:

Necessary cookies help make a website usable by enabling basic functions like page navigation and access to secure areas of the website. The website cannot function properly without these cookies and for these cookies, consent is not required.

Preference or Functionality cookies enable a website to remember information about the user’s choice in the past that changes the way the website behaves or looks, like the preferred language or the region.

Statistics or Performance cookies help website owners to understand how visitors use and interact with their website by collecting and reporting information anonymously, such as which pages are visited most frequently, or which links are clicked on.

Marketing cookies, also known as Advertising or Targeting cookies, are used to track visitors' online activity across websites. The intention is to display ads that are relevant and engaging for the individual user or limit the times an ad is displayed and thereby are more valuable for publishers and third-party advertisers.



HOW LONG DO COOKIES LAST?

Depending on the duration that a cookie is stored on the user’s device, cookies can be classified as Session or Persistent.
Session cookies are temporary cookies and are automatically deleted when the user closes his browser, while Persistent cookies are cookies that remain for a specific amount of time (their expiration date can vary from minutes to days or even years).


HOW TO CONTROL COOKIES ON THIS WEBSITE

FoodKing will only set cookies after you have provided your consent to the use of cookies through the cookies banner upon your visit to our website. For Necessary cookies, we do not need your consent.

You have the right to withdraw your consent or change your preferences regarding the use of cookies at any time. You can do this by accessing our Cookies banner through the “Cookie Settings” button.



YOUR RIGHTS

If you have a concern about the way we are collecting or using cookies, you should raise your concern with us in the first instance or directly to the office of the Commissioner for the Protection of Private Data. ",
                    'menu_section_id' => 2,
                    'template_id'     => 0,
                    'status'          => Status::ACTIVE,
                    'created_at'      => now(),
                    'updated_at'      => now()
                ],
                [
                    'title'           => 'About Us',
                    'slug'            => 'about-us',
                    'description'     => "Who We Are iNiLabs is the maker of GoSchool Education ERP, iTest Online Exam, FoodExpress Multi Vendor eCommerce, QuickPass Visitor Management and many other exclusive Web and Mobile Applications. Our existence depends on making ERPs and SaaS products that help to scale your business.Our Goal We create Innovative Tools to Empower Small Businesses Around the World Our main focus is to deliver high quality and scalable custom PHP applications using Latest Web and Mobile Technologies. We mostly focus our psychic coding abilities, latest development trends and best practices.Origin Story iNiLabs initially began its journey back in 2014. After gathering enough experience, we launched our company in August 2016 as a small web development startup with a renewed confidence and larger vision. We worked our way through many challenges and established ourselves as a Software Development company. iNiLabs now has many amazing products including GoSchool Education ERP, iTest Online Exam, FoodExpress Multi Vendor eCommerce, QuickPass Visitor Management and many other exclusive Web and Mobile Applications ERP and many more. We have 100, 000+ users and businesses using our solutions.",
                    'menu_section_id' => 2,
                    'template_id'     => 0,
                    'status'          => Status::ACTIVE,
                    'created_at'      => now(),
                    'updated_at'      => now()
                ],
                [
                    'title'           => 'Contact Us',
                    'slug'            => 'contact-us',
                    'description'     => "Every day, more than 1000 guests visit FoodKing restaurants around the city. And they do so because our restaurants are known for serving high-quality, great-tasting, and affordable food. Our commitment to premium ingredients, signature recipes, and friendly dining experiences is what has defined our brand for more than 5 successful years.",
                    'menu_section_id' => 2,
                    'template_id'     => 1,
                    'status'          => Status::ACTIVE,
                    'created_at'      => now(),
                    'updated_at'      => now()
                ],
            ]);
        }
    }
}
