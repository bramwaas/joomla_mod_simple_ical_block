# joomla_mod_simple_ical_block

Block module that displays events from a public google calendar or iCal file.

=== Simple iCal Block ===
Plugin name: Simple iCal Block
Contributors: bramwaas
Tags: Event Calendar, Google Calendar, iCal, Events, Block, Calendar, iCalendar, Outlook, iCloud
Requires at least Joomla: 4.0
Tested up to: 5.0.3
Requires PHP: 7
Stable tag: trunk
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html
 
 
== Description ==

Simple block to display events from a public google calendar, microsoft office outlook calendar or an other iCal file, in the style of your website.

This simple block fetches events from a public google calendar (or other calendar in iCal format) and displays them in simple list allowing you to fully adapt to your website by applying all kinds of CSS. 
Google offers some HTML snippets to embed your public Google Calendar into your website.
These are great, but as soon as you want to make a few adjustments to the styling, that goes beyond changing some colors, they're not enough.

== Module Features ==

* Calendar block module to display appointments/events of a public Google calendar or other iCal file.
* Small footprint, uses only Google ID of the calendar, or ICS link for Outlook, or Url of iCal file, to get event information via iCal
* Merge more calendars into one module/block
* Manage events in Google Calendar, or other iCalendar source NOT in this module
* Fully adaptable to your website with CSS. Output in unordered list with Bootstrap 4 or 5 listgroup classes and toggle for collapsed details.
* Choose date / time format that best suits your website in settings screen.
* Displays per event DTSTART, DTEND, SUMMARY, LOCATION and DESCRIPTION. DTSTART is required other components are optional. 
* Displays most common repeating events. Frequency Yearly, Monthly, Weekly, Dayly (not Hourly, Minutely and smaller periods)    
* Basic support for filter on Categories Warning: MS Outlook does not share categories via iCal now. Google and iCloud calendar don't support categories at all. So this will not work with these calendars.   

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

* Do the usual setup procedure System/(Install )Extensions/Install from Web, find 'Simple iCal Block' follow the standard procedure to install the module from here,   
Or find the url on github and use System/(Install )Extensions/Install from URL
Or download from github and then  system/(Install )extensions/Upload package file... activating.   
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
Choose the calendar you want to share (in browser layout on the left panel). On that calendar's line, click the show calendar information icon (a person cropped into a circle) on the right side of the line. In the pop up Calendar Sharing check the box Public Calendar. You see the url below something like webcal://p59-caldav.icloud.com/published/2/MTQxNzk0NDA2NjE0MTc5AAAAAXt2Dy6XXXXXPXXxuZnTLDV9xr6A6_m3r_GU33Qj. Click on Copy Link and OK. Paste that in the "Calendar ID, or iCal URL" field of the widget (before version 1.3.1 you had to change webcal in https)    

= Error: cURL error 28: Operation timed out after 5000 milliseconds with 0 bytes received =

Probably the calendar is not public (yet), you can copy the link before the agenda is actually published. Check if the agenda has already been published and try again.

= I only see the headline of the calendar, but no events =

There are no events found within the selection. Test e.g. with an appointment for the next day and refresh the cache or wait till the cache is refreshed.
Check if you can download the ics file you have designated in the block with a browser. At least if it is a text file with the first line "BEGIN:VCALENDAR" and further lines "BEGIN:VEVENT" and lines "END:VEVENT".   

= I only see the title of the calendar, and the text 'Processing' even after waiting more the a minute, or a message &#61 Code: undefined &#61;	Msg: HTTP error, status &#61; 500  =

Probably you have chosen the setting "Use Client timezone settings, with REST" in "Use client timezone settings". With this setting active, at first the widget will be presented as a placeholder with only the title and the text processing. In the HTML of this placeholder are also some ID\'s as parameters for the javascript REST call to fetch the content after the page is loaded. This fetch is not executed (correct).   
To work correct Javascript must be enabled in a browser with version newer than 2016 but not in Internet Explorer.  
This is probably caused because the javascript view file with the fetch command is not loaded e.g. in the editor of Elementor or an other pagebuilder that tries to show a preview of the widget but does not load the necessary Javascript. This is a known issue, you could work around it by first set "Use WordPress timezone settings, no REST" until you are satisfied with all the other settings and then set "Use Client timezone ...".
If you change the Sibid without clicking the Update button, the new Sibid may already be saved in the plugin options for the REST call, but not in the block attributes. If you still click Update, the problem will be resolved.   
The REST call might also have failed by other reasons, then another try would probably solve the issue, but I have never seen that in testing.   

= Can I use an event calendar that only uses days, not times, like a holiday calendar? =

 Yes you can,  I have tested this with [https://p24-calendars.icloud.com/holiday/NL_nl.ics](https://p24-calendars.icloud.com/holiday/NL_nl.ics) .

= How do I set different colours and text size for the dates, the summary, and the details? =

There is no setting for the color or font of parts in this plugin.
My philosophy is that layout and code/content should be separated as much as possible.
Furthermore, the plugin should seamlessly fit the style of the website and be fully customizable via CSS

So for color and font, the settings of the template are used and are then applied via CSS.
But you can give each element within the plugin its own style (such as color and font size) from the theme via CSS.

If you know your template css well and it contains classes you want to use on these fields you can add those class-names in
the Settings Tab Advanced : &#34;SUFFIX GROUP CLASS:&#34;, &#34;SUFFIX EVENT START CLASS:&#34; and &#34;SUFFIX EVENT DETAILS CLASS:&#34;

Otherwise you can add a block of additional CSS (or extra css or user css or something like that), which is possible with most templates.   
IMPORTANT:   
In order to target the CSS very specifically to the simple-ical-block, it is best to enter something unique in the settings of the module Tab Advanced in &#34;HTML ANCHOR&#34;, for example &#39;Simple-ical-Block-1&#39; the code translated into a high-level ID of the module.
With the next block of additional CSS you can make the Dates red and 24 px, the Summary blue and 16 px,
and the Details green with a gray background.

~~~
/*additional CSS for Simple-ical-Block-1 */
&#35;Simple-ical-Block-1 .ical-date {
color: #ff0000;
font-size: 24px;
}
&#35;Simple-ical-Block-1 .ical_summary {
color: #0000ff;
font-size: 16px;
}
&#35;Simple-ical-Block-1 .ical_details {
color: #00ff00;
background-color: gray;
}
/*end additional CSS for Simple-ical-Block-1 */
~~~

= How do I filter on categories =

Warning: the plugin only supports categories that are available in the iCal file. Microsoft Outlook does support categories but does not share them via the ical file.
When the ical contains categories there are three options in the advanced section to use them.
      
-- Categories Filter Operator:
Here you can choose how to compare the filter categories with the event categories.  
- empty no filtering.
- ANY is true if at least one of the elements of the filter set is present in the event set, or in other words the filter set intersects the event set, the intersection contains at least one element. This seems to me to be the most practical operator.  
- ALL is true if all elements of the filter set exist in the event set, or in other words, the intersection contains the same number of elements as the filter set. The event set can contain other elements.  
- NOTANY is true if ANY is NOT true. The intersection is empty.
- NOTALL is true if ALL is NOT true. The intersection contains fewer elements than the filter set.   
- A special case are events without categories. In the filter, the plugin handles this as if the category were a null string ("").        
     
-- Categories Filter List:
- List of filter categories separated by a comma (not in double quotes). If a category contains a comma, you must add a backslash (\,) to it. A null string is created as a category if nothing is entered in the list or if the list ends with a comma, or if there are two comma separators immediately next to each other.             
- Categories (at least in this plugin) behave like simple tags and have no intrinsic meaning or relationship. So if you want to select all events with category flower, rose or tulip, you have to add them all to the filter list. With category flower, you don't automatically select rose and tulip too    
  
-- Display categories with separator:
- Here you can choose to display the list of event categories after the summary and with what separator. If you leave this field empty, the list will not be displayed.

If the event contains categories, the list of categories of this event cleaned as classes (removed spaces etc.) is added to the  html-classes of the event (to the list-group-item). 

== Documentation ==

* Gets calendar events via iCal url or google calendar ID
* Merge more calendars into one module/block
* Displays selected number of events, or events in a selected period from now as listgroup-items
* Displays only events in a selected period with a length of the setting "Number of days after today with events" from now limited by the time of the day or the beginning of the day at the start and the and of the at the end.
* Displays events in timezone of Joomla setting, or in Clients timezone with javascript REST call fetched from the clients browser.
* Displays event start-date and summary; toggle details, description, start-, end-time, location. 
* Displays most common repeating events 
* Frequency Yearly, Monthly, Weekly, Dayly (not parsed Hourly, Minutely ...), INTERVAL (default 1), WKST (default MO)
* End of repeating by COUNT or UNTIL
* By day month, monthday or setpos (BYDAY, BYMONTH, BYMONTHDAY, BYSETPOS) no other by...   
  (not parsed: BYWEEKNO, BYYEARDAY, BYHOUR, BYMINUTE, RDATE)
* Exclude events on EXDATE from recurrence set (after evaluating BYSETPOS)
* Respects Timezone and Day Light Saving time. Build and tested with Iana timezones as used in php, Google, and Apple now also tested with Microsoft timezones and unknown timezones. For unknown timezone-names using the default timezone of te site  (probably the local timezone set in Joomla administration).  

=== Recurrent events, Timezone,  Daylight Saving Time ===

Most users don't worry about time zones. The timezonesettings for the Joomla installation, the calendar application and the events are all the same local time.   
In that case this block displays all the recurrent events with the same local times. The block uses the properties of the starttime and duration (in seconds) of the first event to calculate the repeated events. Only when a calculated time is removed during the transition from ST (standard time, wintertime) to DST (daylight saving time, summertime) by turning the clock forward one hour, both the times of the event (in case the starttime is in the transition period) or only the endtime (in case only the endtime is in the transition period)  of the event are pushed forward with that same amount.    
But because the transition period is usually very early in the morning, few events will be affected by it.   
If a calculated day does not exist (i.e. February 30), the event will not be displayed. (Formally this should also happen to the time in the transition from ST to DST)   

For users in countries that span more time zones, or users with international events the calendar applications can add a timezone to the event times. So users in other timezones will see the event in the local time in there timezone (as set in timezone settings of Joomla) corresponding with the time.    
The block uses the starttime, the timezone of the starttime and the duration in seconds of the starting event as starting point for the calculation of repeating events. So if the events startime timezone does'not use daylight savings time and and timezone of the block (i.e. the Joomla timezone setting) does. During DST the events are placed an hour later than during ST. (more precisely shift with the local time shift). Similar the events will be shift earlier when the timezone of the starttime has DST and the timezone of the block not.   

Of course the same effect is achieved when you schedule the events in UTC time despite using local time DST in your standard calendar.     
In these cases, a special effect can be seen of using the same times twice in the transition from DST to ST. If an event lasts less than an hour. If the event starts in the last hour of DST then it ends in the first hour of ST in between the local clocks are turned back one hour. According to the local clock, the end time is therefore before the start time. And the block shows it like this too. The same also applies to Google and Outlook calendar.   
Theoretically this could als happen with recurrent events in the same timezone with DST. In my test I have seen this with Google calendar but not with the block. PHP and therefore the block uses the second occurence if the result of the calculation is a time that is twice available (at least in the version of PHP I use), but using the first occurence like Google does is just as good.    

Test results and comparison with Google and Outlook calendar [with the wordpress plugin](https://wordpress.org/plugins/simple-google-icalendar-widget/) have been uploaded as DayLightSavingTime test.xlsx.
  
=== From the ical specifications ===

~~~
see http://www.ietf.org/rfc/rfc5545.txt for specification of te ical format,
or https://icalendar.org/iCalendar-RFC-5545/
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
  |BYSETPOS    |Limit    |Limit   |Limit    |Limit   |
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
* 2.5.1 solve issue #29 "Joomla Version 5.2.1" by Grey-Sheep replaced default "1" for setting "Categories Filter Operator" by "" "No filter". And tested with clean Joomla 5.2.1. and new install of the module.  
* 2.5.0 add support for filter and display categories. replace strip_tags(...) by InputFilter::clean(...) to improve security by also filtering attributes like wp_kses    
* 2.4.1 Convert modules to service provider. Remove some small flaws like php warnings and JS console.log 
* 2.4.0 Tie moving display events window created by Now and 'Number of days after today' to the display time instead of the data-retrieve/cache time. Make it possible to let the window start at 0H00 and end at 23H59 local time of the startdate and enddate of the window in addition to the current solution where both ends are at the time of the day the data is displayed/retrieved. Add &lt;span class="dsc"&gt; to description output to make it easier to refer to in css. Remove HTML for block title when title is empty. Add unescape \\ to \ and improve \, to ,   \; to ;  chars that should be escaped following the text specification. Tested with J5.0.3 and J4.4.3.    
* 2.2.1 20240123 after an issue of black88mx6 in WP support forum: don't display description line when excerpt-length = 0
* 2.2.0 after an issue of gonzob (@gonzob) in WP support forum: 'Bug with repeating events
' improved handling of EXDATE so that also the first event of a recurrent set can be excluded.   
Basic parse Recurrence-ID (only one Recurrence-ID event to replace one occurrence of the recurrent set) to support changes in individual recurrent events in Google Calendar. Remove _ chars from UID.
* 2.1.5 tested with joomla 5 added compatibility to 5.0.99
* 2.1.4 After a feature request of achimmm (in github on Joomla module) added optional placeholder HTML output when no upcoming events are avalable. Also added optional output after the events list (when upcoming events are available).
* 2.1.3 In response to a support issue of (@marijnvr) (on WP plugin). New lay-out for block with first date line on a higer level li. 'Start with summary' toggle-setting changed in 'layout' select-setting with options 'Startdate higher level', 'Start with summary', 'Old style'.   
* 2.1.1 Solved Warning: Array to string conversion in .../Transport/Curl.php on line 183 that occured after using php 8.  
* 2.1.0 Support more calendars in one module/block. Support DURATION of event. Move processing 'allowhtml' complete out Parser to template/block. 
  Use properties in IcsParser to limit copying of input params in several functions.
  Solved issue: Warning: date() expects at most 2 parameters, 3 given in ...IcsParser.php on line 542 caused by wp_date() / date() replacement.    
  Support BYSETPOS in response to a github issue on the WP block of peppergrayxyz. Support WKST.   
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
