=== Simple iCal Block ===
Plugin name: Simple iCal Block
Contributors: bramwaas
Tags: Event Calendar, Google Calendar, iCal, Events, Block, Calendar, iCalendar, Outlook, iCloud
Requires at least: 4.0.0
Tested up to: 4.2
Requires PHP: 5.3.0
Stable tag: trunk
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html
 
Block module that displays events from a public google calendar or iCal file.
 
== Description ==

Simple block to display events from a public google calendar, microsoft office outlook calendar or an other iCal file, in the style of your website.

This simple block fetches events from a public google calendar (or other calendar in iCal format) and displays them in simple list allowing you to fully adapt to your website by applying all kinds of CSS. 
Google offers some HTML snippets to embed your public Google Calendar into your website.
These are great, but as soon as you want to make a few adjustments to the styling, that goes beyond changing some colors, they're not enough.

== Module Features ==

* Calendar block module to display appointments/events of a public Google calendar or other iCal file.
* Small footprint, uses only Google ID of the calendar, or ICS link for Outlook, or Url of iCal file, to get event information via iCal
* Merge more calendars into one module/block
* Manage events in Google Calendar, or other iCalendar source.
* Fully adaptable to your website with CSS. Output in unordered list with Bootstrap 4 or 5 listgroup classes and toggle for collapsed details.
* Choose date / time format in settings screen that best suits your website.
* Displays per event DTSTART, DTEND, SUMMARY, LOCATION and DESCRIPTION. DTSTART is required other components are optional. 
* Displays most common repeating events. Frequency Yearly, Monthly, Weekly, Dayly (not Hourly, Minutely and smaller periods)    

Adjusted settings for start with summary:  
Start with summary.: "true"  
Date format first line: ".<\b\r>l jS \o\f  F"  
Enddate format first line: " - l jS \o\f F"  
Time format time summary line: " G:i"   
Time format end time summary line: " - G:i"   
Time format start time: "<>"  
Time format end time: "<>"  
Tag for summary: "strong" 
  
== Installation ==

* Do the usual setup procedure... you know... downloading from github system/install/extensions/Upload package file... activating.   
Or find the url on github and use System/Install/Extensions/Install from URL
* Then add the module on the desired position with Conten/Site modules/New or System/Mange/Sitemodules/New
* If you want the block in the content area enable plugin Content - Load Modules in System/Manage/Plugins and first create a position in your article content   
  as described in the plugin.
* Fill out all the necessary configuration fields.
 In Calendar ID enter the calendar ID displayed by Google Calendar, or complete url of a  Google calendar or other iCal file.
 You can find Google calendar ID by going to Calendar Settings / Calendars, clicking on the appropriate calendar, scrolling all the way down to find the Calendar ID at the bottom under the Integrate Calendar section. There's your calendar id.
* You're done!

== Frequently Asked Questions ==

= How to use Google Calendar? =

First you have to share your calendar to make it public available, or to create a public calendar. Private calendars cannot be accessed by this plugin.
Then use the public iCal address or the Google calendar ID.
[More details on Google support](https://support.google.com/calendar/answer/37083)

= Where do I find the Google Calendar Id? =

 You can find Google calendar ID by going to Calendar Settings / Calendars, clicking on the appropriate calendar, scrolling all the way down to find the Calendar ID at the bottom under the Integrate Calendar section. There's your calendar id.
 [More details on Google support](https://support.google.com/calendar/answer/37083#link)
 
= How to merge more calendars into one module/block =  

Fill a comma separated list of ID's in the Calendar ID field.      
Optional you can add a html-class separated by a semicolon to some or all ID's to distinguish the descent in the lay-out of the event.   
E.g.: #example;blue,https://p24-calendars.icloud.com/holiday/NL_nl.ics;red     
Events of #example will be merged with events of NL holidays; html-class "blue" is added to all events of #example, html-class "red" to all events of NL holidays. 

= Can I use HTML in the description of the appointement?  =

You can use HTML in the most Calendars, but the result in the plugin may not be what you expect.    
First: The original iCalendar standard allowed only plain text as part of an event description. Thus probably most calendars will only give the plain text in the Description in the iCal output.   
Secondly: For security reasons  this plugin filters the HTML to convert characters that have special significance in HTML to the corresponding HTML-entities.
  
But in this module if you trust the output of the calendar application you can set a checkbox to allow safe html in the output. So if you manage to get the HTML in the Description and you set the checkbox to allow safe html you can get that html in the output, with exception of the tags that are not considered safe like SCRIPT and unknown tags.          
And with the current version  of Google Calendar you can put some HTML in the Description output. (April 2022) I saw the  &lt;a&gt; (link),  &lt;b&gt; (bold text),  &lt;i&gt; (italic text),  &lt;u&gt; (underlined text) and  &lt;br&gt; (linebreak) tags in a iCal description. They will all come through with "Allow safe html" checkbox on. Probably even more is possible, but Google can also decide to comply more to the standard.   
With Microsoft Outlook the HTML tags were filtered away and did not reach the iCal description         

In case you have all kinds of HTML in your appointments a plugin that uses the API of te calendar-application might be a better choice for you.     

= How to use Microsoft Office Outlook Calendar? =

First you have to share your calendar to make it public available, or to create and share a public calendar. Private calendars cannot be accessed by this plugin.
Then publish it as  an ICS link and use this link address. (something like https://outlook.live.com/owa/calendar/00000000-0000-0000-0000-000000000000/.../cid-.../calendar.ics) 
[More details on Microsoft Office support](https://support.office.com/en-us/article/share-your-calendar-in-outlook-on-the-web-7ecef8ae-139c-40d9-bae2-a23977ee58d5)

= How to use Apple Calendar (iCloud Mac/ios)? =
Choose the calendar you want to share. On the line of that calendar click on the radio symbol (a dot with three quart circles) right in that line. In the pop up Calendar Sharing check the box Public Calendar. You see the url below something like webcal://p99-caldav.icloud.com/published/2/MTQxNzk0NDA2NjE0MTc5AAAAAXt2Dy6XXXXXPXXxuZnTLDV9xr6A6_m4r_GU83Qj. Click on Copy Link and OK. Paste that in the "Calendar ID, or iCal URL" field of the block.   
[More details on the MacObserver](https://www.macobserver.com/tips/quick-tip/icloud-configure-public-calendar)

= Error: cURL error 28: Operation timed out after 5000 milliseconds with 0 bytes received =

Probably the calendar is not public (yet), you can copy the link before the agenda is actually published. Check if the agenda has already been published and try again.

= I only see the headline of the calendar, but no events =

There are no events found within the selection. Test e.g. with an appointment for the next day and refresh the cache or wait till the cache is refreshed.
Check if you can download the ics file you have designated in the block with a browser. At least if it is a text file with the first line "BEGIN:VCALENDAR" and further lines "BEGIN:VEVENT" and lines "END:VEVENT".   

= Can I use an event calendar that only uses days, not times, like a holiday calendar? =

 Yes you can,  I have tested this with [https://p24-calendars.icloud.com/holiday/NL_nl.ics](https://p24-calendars.icloud.com/holiday/NL_nl.ics) .

== Documentation ==

* Gets calendar events via iCal url or google calendar ID
* Merge more calendars into one module/block
* Displays selected number of events, or events in a selected period from now as listgroup-items
* Displays event start-date and summary; toggle details, description, start-, end-time, location. 
* Displays most common repeating events 
* Frequency Yearly, Monthly, Weekly, Dayly (not parsed Hourly, Minutely ...)
* End of repeating by COUNT or UNTIL
* Exclude events on EXDATE from repeat 
* By day month or by monthday (BYDAY, BYMONTH, BYMONTHDAY) no other by
  (not parsed: BYYEARDAY, BYSETPOS, BYHOUR, BYMINUTE, WKST)  
* Respects Timezone and Day Light Saving time. Build and tested with Iana timezones as used in php, Google, and Apple now also tested with Microsoft timezones and unknown timezones. For unknown timezone-names using the default timezone of te site  (probably the local timezone set in Joomla administration).  

=== Recurrent events, Timezone,  Daylight Saving Time ===

Most users don't worry about time zones. The timezonesettings for the Joomla installation, the calendar application and the events are all the same local time.   
In that case this block displays all the recurrent events with the same local times. The block uses the properties of the starttime and duration (in seconds) of the first event to calculate the repeated events. Only when a calculated time is removed during the transition from ST (standard time, wintertime) to DST (daylight saving time, summertime) by turning the clock forward one hour, both the times of the event (in case the starttime is in the transition period) or only the endtime (in case only the endtime is in the transition period)  of the event are pushed forward with that same amount.    
But because the transition period is usually very early in the morning, few events will be affected by it.   
If a calculated day does not exist (i.e. February 30), the event will not be displayed.

For users in countries that span more time zones, or users with international events the calendar applications can add a timezone to the event times. So users in other timezones will see the event in the local time in there timezone (as set in timezone settings of Joomla) corresponding with the time.    
The block uses the starttime, the timezone of the starttime and the duration in seconds of the starting event as starting point for the calculation of repeating events. So if the events startime timezone does'not use daylight savings time and and timezone of the block (i.e. the Joomla timezone setting) does. During DST the events are placed an hour later than during ST. (more precisely shift with the local time shift). Similar the events will be shift earlier when the timezone of the starttime has DST and the timezone of the block not.   

Of course the same effect is achieved when you schedule the events in UTC time despite using local time DST in your standard calendar.     
In these cases, a special effect can be seen of using the same times twice in the transition from DST to ST. If an event lasts less than an hour. If the event starts in the last hour of DST then it ends in the first hour of ST in between the local clocks are turned back one hour. According to the local clock, the end time is therefore before the start time. And the block shows it like this too. The same also applies to Google and Outlook calendar.   
Theoretically this could als happen with recurrent events in the same timezone with DST. In my test I have seen this with Google calendar but not with the block. PHP and therefore the block uses the second occurence if the result of the calculation is a time that is twice available (at least in the version of PHP I use), but using the first occurence like Google does is just as good.    

Test results and comparison with Google and Outlook calendar [with the wordpress plugin](https://wordpress.org/plugins/simple-google-icalendar-widget/) have been uploaded as DayLightSavingTime test.xlsx.
  
=== From the ical specifications ===
~~~
see http://www.ietf.org/rfc/rfc5545.txt for specification of te ical format.
(see 3.3.10. [Page 38] Recurrence Rule in specification
  .____________._________.________._________.________.
  |            |DAILY    |WEEKLY  |MONTHLY  |YEARLY  |
  |____________|_________|________|_________|________|   
  |BYMONTH     |Limit    |Limit   |Limit    |Expand  |
  |____________|_________|________|_________|________|
  |BYMONTHDAY  |Limit    |N/A     |Expand   |Expand  |
  |____________|_________|________|_________|________|
  |BYDAY       |Limit    |Expand  |Note 1   |Note 2  |
  |____________|_________|________|_________|________|
 
    Note 1:  Limit if BYMONTHDAY is present; 
             otherwise, special expand for MONTHLY.

    Note 2:  Limit if BYYEARDAY or BYMONTHDAY is present; otherwise,
             special expand for WEEKLY if BYWEEKNO present; otherwise,
             special expand for MONTHLY if BYMONTH present; otherwise,
             special expand for YEARLY.
~~~

== Copyright and License ==

This project is licensed under the [GNU GPL](https://www.gnu.org/licenses/gpl-3.0.html), version 3 or later.
2022&thinsp;&ndash;&thinsp;2022 &copy; [Bram Waasdorp](http://www.waasdorpsoekhan.nl).

== Upgrade Notice ==

* works with Joomla 4 or higher.

== Changelog ==
* 2.1.0 Support more calendars in one module/block. Support DURATION of event. Move processing 'allowhtml' complete out Parser to template/block. 
  Use properties in IcsParser to limit copying of input params in several functions.  
* 2.0.0 major and minor vesion number aligned with those of Wordpress block with the same functionality and the same code for the IcsParser block apart from CMS specific functions (get_option('timezone_string') / Factory::getApplication()->get('offset'), wp_transient / cache type 'output' and wp_remote_get / Joomla\Http\Http->get()) and temporary wp_date() / date().
* 0.0.7 added Accept-Encoding: '' to http request to tell curl to handle compressed results (known by the server) correct.
* 0.0.6 added translations and adjustments to comply with JED checker.
* 0.0.5 added documentation tab in settings form.
* 0.0.4 replace transient by cache type 'output'; split transientId in cachegroup and cacheID to distinguish the group in a.o. System/Clear cache
* 0.0.3 module works in Joomla 4, with #example, with file, and with requests from google or outlook. 
  Output is comparable with output of WP block but more testing and clean up needs to be done.
* 0.0.0 imported V2.0.3 of my Wordpress plugin [Simple Google Calendar Outlook Events Block Widget](https://wordpress.org/plugins/simple-google-icalendar-widget/) and made modifications to let it work with Joomla 4.
* copied src/IcsParser.php with IcsParser class from WP includes/IcsParser.php; replaced wp specific functions like wp_date() wp_remote_get() and
  get_option('timezone_string') by PHP or Joomla alternatives.
* created transient functions in SimpleicalblockHelper based on functionality of WP transient functions. Used the new transient functions
  in ICsParser.
* copied most of content of default.php from WP display_block() in includes/SimpleicalBlock; replaced wp specific functions like wp_date() wp_remote_get() and
  get_option('timezone_string') by PHP or Joomla alternatives. Because php date() has no translation like wp_date() more modifications made to
  use Joomla Date object when output needs to be translated. 
  Used strip_tags with allowed html for wp_kses(,'post') and copied most of the code of WP sanitize_html_class() 
  to sanitize_html_class() in SimpleicalblockHelper added space as valid character to accommodate more classes in one class attribute.
* copied $allowed_tags_sum and other preparation of parameters/attributes functionality of render_block() in SimpleicalblockHelper    
  from includes/SimpleicalBlock.php
* created config/fields in mod_simpleical_block.xml based on WP edit elements in simple-ical-block.js 
  and form() function in simple-google-icalendar-widget.php. Removed title as we already have a module title. Filled blockid with module.id.
* created CleartransientnowRule to handle action on field clear_cache_now like it is done in the WP widget; 
   i.e. deleting the transient when the parameters are saved and clear_cache_now = true.
