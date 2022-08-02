<?php
/**
 * a simple ICS parser.
 *
 * note that this class does not implement all ICS functionality.
 *   bw 20220630 copied from Wordpress simple-google-icalendar-widget version 2.0.3
 * Version: 0.0.4
 *  replace WP transient_functions by  SimpleicalblockHelper::transient_functions ;
 *  replace wp_remote_get by Http->get(), create Http object in var $http  construct and thus necesary to instantiate the class
 *  replace get_option('timezone_string') and wp_timezone by Factory::getApplication()->get('offset') and ...
 *  replace wp_date( by date(
 *  replace transient by cache type 'output'; split transientId in cahegroup and cacheID to distinguish the group in system clear cache
 
 */
namespace WaasdorpSoekhan\Module\Simpleicalblock\Site;

use Joomla\CMS\Cache\Controller\OutputController;
use Joomla\CMS\Factory;
use Joomla\Http\Http;

// use WaasdorpSoekhan\Module\Simpleicalblock\Site\Helper\SimpleicalblockHelper;

class IcsParser {
    
    const TOKEN_BEGIN_VEVENT = "BEGIN:VEVENT";
    const TOKEN_END_VEVENT = "END:VEVENT";
    const TOKEN_BEGIN_VTIMEZONE = "\nBEGIN:VTIMEZONE";
    const TOKEN_END_VTIMEZONE = "\nEND:VTIMEZONE";
    /**
     * @var string events to display in example
     * EOL's and one space before second description line are important.
     */
    private static $example_events = 'BEGIN:VCALENDAR
BEGIN:VEVENT
DTSTART:20220626T150000
DTEND:20220626T160000
RRULE:FREQ=WEEKLY;INTERVAL=3;BYDAY=SU,WE,SA
UID:a-1
DESCRIPTION:Description event every 3 weeks sunday wednesday and saturday. t
 est A-Z.\nLine 2 of description.
LOCATION:Located at home or somewhere else
SUMMARY: Every 3 weeks sunday wednesday and saturday
END:VEVENT
BEGIN:VEVENT
DTSTART:20220629T143000
DTEND:20220629T153000
RRULE:FREQ=MONTHLY;COUNT=24;BYMONTHDAY=29
UID:a-2
DESCRIPTION:
LOCATION:
SUMMARY:Example Monthly day 29
END:VEVENT
BEGIN:VEVENT
DTSTART;VALUE=DATE:20220618
DTEND;VALUE=DATE:20220620
RRULE:FREQ=MONTHLY;COUNT=13;BYDAY=4SA
UID:a-3
DESCRIPTION:Example Monthly 4th weekend
LOCATION:Loc. unknown
SUMMARY:X Monthly 4th weekend
END:VEVENT
END:VCALENDAR';
    
    /**
     *
     * @var array english abbreviations and names of weekdays.
     */
    private static $weekdays = array(
         'MO' => 'monday',
        'TU' => 'tuesday',
        'WE' => 'wednesday',
        'TH' => 'thursday',
        'FR' => 'friday',
        'SA' => 'saturday',
        'SU' => 'sunday',
    );
    /**
     * Maps Windows (non-CLDR) time zone ID to IANA ID. This is pragmatic but not 100% precise as one Windows zone ID
     * maps to multiple IANA IDs (one for each territory). For all practical purposes this should be good enough, though.
     *
     * Source: http://unicode.org/repos/cldr/trunk/common/supplemental/windowsZones.xml
     * originally copied from ics-calendar.7.2.0
     *
     * @var array
     */
    private static $windowsTimeZonesMap = array(
        'AUS Central Standard Time'       => 'Australia/Darwin',
        'AUS Eastern Standard Time'       => 'Australia/Sydney',
        'Afghanistan Standard Time'       => 'Asia/Kabul',
        'Alaskan Standard Time'           => 'America/Anchorage',
        'Aleutian Standard Time'          => 'America/Adak',
        'Altai Standard Time'             => 'Asia/Barnaul',
        'Arab Standard Time'              => 'Asia/Riyadh',
        'Arabian Standard Time'           => 'Asia/Dubai',
        'Arabic Standard Time'            => 'Asia/Baghdad',
        'Argentina Standard Time'         => 'America/Buenos_Aires',
        'Astrakhan Standard Time'         => 'Europe/Astrakhan',
        'Atlantic Standard Time'          => 'America/Halifax',
        'Aus Central W. Standard Time'    => 'Australia/Eucla',
        'Azerbaijan Standard Time'        => 'Asia/Baku',
        'Azores Standard Time'            => 'Atlantic/Azores',
        'Bahia Standard Time'             => 'America/Bahia',
        'Bangladesh Standard Time'        => 'Asia/Dhaka',
        'Belarus Standard Time'           => 'Europe/Minsk',
        'Bougainville Standard Time'      => 'Pacific/Bougainville',
        'Canada Central Standard Time'    => 'America/Regina',
        'Cape Verde Standard Time'        => 'Atlantic/Cape_Verde',
        'Caucasus Standard Time'          => 'Asia/Yerevan',
        'Cen. Australia Standard Time'    => 'Australia/Adelaide',
        'Central America Standard Time'   => 'America/Guatemala',
        'Central Asia Standard Time'      => 'Asia/Almaty',
        'Central Brazilian Standard Time' => 'America/Cuiaba',
        'Central Europe Standard Time'    => 'Europe/Budapest',
        'Central European Standard Time'  => 'Europe/Warsaw',
        'Central Pacific Standard Time'   => 'Pacific/Guadalcanal',
        'Central Standard Time (Mexico)'  => 'America/Mexico_City',
        'Central Standard Time'           => 'America/Chicago',
        'Chatham Islands Standard Time'   => 'Pacific/Chatham',
        'China Standard Time'             => 'Asia/Shanghai',
        'Cuba Standard Time'              => 'America/Havana',
        'Dateline Standard Time'          => 'Etc/GMT+12',
        'E. Africa Standard Time'         => 'Africa/Nairobi',
        'E. Australia Standard Time'      => 'Australia/Brisbane',
        'E. Europe Standard Time'         => 'Europe/Chisinau',
        'E. South America Standard Time'  => 'America/Sao_Paulo',
        'Easter Island Standard Time'     => 'Pacific/Easter',
        'Eastern Standard Time (Mexico)'  => 'America/Cancun',
        'Eastern Standard Time'           => 'America/New_York',
        'Egypt Standard Time'             => 'Africa/Cairo',
        'Ekaterinburg Standard Time'      => 'Asia/Yekaterinburg',
        'FLE Standard Time'               => 'Europe/Kiev',
        'Fiji Standard Time'              => 'Pacific/Fiji',
        'GMT Standard Time'               => 'Europe/London',
        'GTB Standard Time'               => 'Europe/Bucharest',
        'Georgian Standard Time'          => 'Asia/Tbilisi',
        'Greenland Standard Time'         => 'America/Godthab',
        'Greenwich Standard Time'         => 'Atlantic/Reykjavik',
        'Haiti Standard Time'             => 'America/Port-au-Prince',
        'Hawaiian Standard Time'          => 'Pacific/Honolulu',
        'India Standard Time'             => 'Asia/Calcutta',
        'Iran Standard Time'              => 'Asia/Tehran',
        'Israel Standard Time'            => 'Asia/Jerusalem',
        'Jordan Standard Time'            => 'Asia/Amman',
        'Kaliningrad Standard Time'       => 'Europe/Kaliningrad',
        'Korea Standard Time'             => 'Asia/Seoul',
        'Libya Standard Time'             => 'Africa/Tripoli',
        'Line Islands Standard Time'      => 'Pacific/Kiritimati',
        'Lord Howe Standard Time'         => 'Australia/Lord_Howe',
        'Magadan Standard Time'           => 'Asia/Magadan',
        'Magallanes Standard Time'        => 'America/Punta_Arenas',
        'Marquesas Standard Time'         => 'Pacific/Marquesas',
        'Mauritius Standard Time'         => 'Indian/Mauritius',
        'Middle East Standard Time'       => 'Asia/Beirut',
        'Montevideo Standard Time'        => 'America/Montevideo',
        'Morocco Standard Time'           => 'Africa/Casablanca',
        'Mountain Standard Time (Mexico)' => 'America/Chihuahua',
        'Mountain Standard Time'          => 'America/Denver',
        'Myanmar Standard Time'           => 'Asia/Rangoon',
        'N. Central Asia Standard Time'   => 'Asia/Novosibirsk',
        'Namibia Standard Time'           => 'Africa/Windhoek',
        'Nepal Standard Time'             => 'Asia/Katmandu',
        'New Zealand Standard Time'       => 'Pacific/Auckland',
        'Newfoundland Standard Time'      => 'America/St_Johns',
        'Norfolk Standard Time'           => 'Pacific/Norfolk',
        'North Asia East Standard Time'   => 'Asia/Irkutsk',
        'North Asia Standard Time'        => 'Asia/Krasnoyarsk',
        'North Korea Standard Time'       => 'Asia/Pyongyang',
        'Omsk Standard Time'              => 'Asia/Omsk',
        'Pacific SA Standard Time'        => 'America/Santiago',
        'Pacific Standard Time (Mexico)'  => 'America/Tijuana',
        'Pacific Standard Time'           => 'America/Los_Angeles',
        'Pakistan Standard Time'          => 'Asia/Karachi',
        'Paraguay Standard Time'          => 'America/Asuncion',
        'Romance Standard Time'           => 'Europe/Paris',
        'Russia Time Zone 10'             => 'Asia/Srednekolymsk',
        'Russia Time Zone 11'             => 'Asia/Kamchatka',
        'Russia Time Zone 3'              => 'Europe/Samara',
        'Russian Standard Time'           => 'Europe/Moscow',
        'SA Eastern Standard Time'        => 'America/Cayenne',
        'SA Pacific Standard Time'        => 'America/Bogota',
        'SA Western Standard Time'        => 'America/La_Paz',
        'SE Asia Standard Time'           => 'Asia/Bangkok',
        'Saint Pierre Standard Time'      => 'America/Miquelon',
        'Sakhalin Standard Time'          => 'Asia/Sakhalin',
        'Samoa Standard Time'             => 'Pacific/Apia',
        'Sao Tome Standard Time'          => 'Africa/Sao_Tome',
        'Saratov Standard Time'           => 'Europe/Saratov',
        'Singapore Standard Time'         => 'Asia/Singapore',
        'South Africa Standard Time'      => 'Africa/Johannesburg',
        'Sri Lanka Standard Time'         => 'Asia/Colombo',
        'Sudan Standard Time'             => 'Africa/Tripoli',
        'Syria Standard Time'             => 'Asia/Damascus',
        'Taipei Standard Time'            => 'Asia/Taipei',
        'Tasmania Standard Time'          => 'Australia/Hobart',
        'Tocantins Standard Time'         => 'America/Araguaina',
        'Tokyo Standard Time'             => 'Asia/Tokyo',
        'Tomsk Standard Time'             => 'Asia/Tomsk',
        'Tonga Standard Time'             => 'Pacific/Tongatapu',
        'Transbaikal Standard Time'       => 'Asia/Chita',
        'Turkey Standard Time'            => 'Europe/Istanbul',
        'Turks And Caicos Standard Time'  => 'America/Grand_Turk',
        'US Eastern Standard Time'        => 'America/Indianapolis',
        'US Mountain Standard Time'       => 'America/Phoenix',
        'UTC'                             => 'Etc/GMT',
        'UTC+12'                          => 'Etc/GMT-12',
        'UTC+13'                          => 'Etc/GMT-13',
        'UTC-02'                          => 'Etc/GMT+2',
        'UTC-08'                          => 'Etc/GMT+8',
        'UTC-09'                          => 'Etc/GMT+9',
        'UTC-11'                          => 'Etc/GMT+11',
        'Ulaanbaatar Standard Time'       => 'Asia/Ulaanbaatar',
        'Venezuela Standard Time'         => 'America/Caracas',
        'Vladivostok Standard Time'       => 'Asia/Vladivostok',
        'W. Australia Standard Time'      => 'Australia/Perth',
        'W. Central Africa Standard Time' => 'Africa/Lagos',
        'W. Europe Standard Time'         => 'Europe/Berlin',
        'W. Mongolia Standard Time'       => 'Asia/Hovd',
        'West Asia Standard Time'         => 'Asia/Tashkent',
        'West Bank Standard Time'         => 'Asia/Hebron',
        'West Pacific Standard Time'      => 'Pacific/Port_Moresby',
        'Yakutsk Standard Time'           => 'Asia/Yakutsk',
    );
    /**
     * The arry of events parsed from the ics file, initial set by parse function.
     *
     * @var    array array of event objects
     * @since  1.5.1 
     */
    protected $events = [];

    /**
     * The Http object to get the ical data (for Joomla
     *
     * @var    \DateTime
     * @since  1.5.1
     */
    protected $now = NULL;
    /**
     * The Http object to get the ical data (for Joomla
     *
     * @var   Joomla\Http\Http
     * @since  0.0.1
     */
    protected $http = NULL;
    /**
     * The timezone string from the configuration.
     *
     * @var   string
     * @since  0.0.1
     */
    protected $timezone_string = 'UTC';
    
    
    /**
     * Constructor.
     *
     * @param
     *
     * @return  $this IcsParser object
     *
     * @since
     */
    public function __construct()
    {
        $this->http = new Http();
        $this->timezone_string = Factory::getApplication()->get('offset');
    }
    /**
     * Parse ical string to individual events
     *
     * @param   string      $str the  content of the file to parse as a string.
     * @param   \datetime   $penddate the max date for the last event to return.
     * @param   int         $pcount   the max number of events to return.
     * @param   array       $instance array of options
     *
     * @return  array       $this->events the parsed event objects.
     *
     * @since
     */
    public function parse($str ,  $penddate,  $pcount, $instance  ) {
        $curstr = $str;
        $haveVevent = true;
        $events = array();
        $this->now = time();
//        $this->now = (new \DateTime('2022-01-01'))->getTimestamp();
        
        $penddate = (isset($penddate) && $penddate > $this->now) ? $penddate : $this->now;
        do {
            $startpos = strpos($curstr, self::TOKEN_BEGIN_VEVENT);
            if ($startpos !== false) {
                // remove BEGIN_VEVENT and END:VEVENT and EOL character(s) \r\n or \n
                $eventStrStart = $startpos + strlen(self::TOKEN_BEGIN_VEVENT);
                $eventStr = substr($curstr, $eventStrStart);
                $endpos = strpos($eventStr, self::TOKEN_END_VEVENT);
                if ($endpos === false) {
                    throw new \Exception('IcsParser->parse: No valid END:VEVENT found.');
                }
                $eventStr = trim(substr($eventStr, 0, $endpos), "\n\r\0");
                $e = $this->parseVevent($eventStr, $instance);
                $events[] = $e;
                // Recurring event?
                if (isset($e->rrule) && $e->rrule !== '') {
                    /* Recurring event, parse RRULE in associative array add appropriate duplicate events
                     * only for frequencies YEARLY, MONTHLY,  WEEKLY and DAYLY
                     * frequency by multiplied by INTERVAL (default INTERVAL = 1)
                     * in a period starting after today() and after the first instance of the event, ending
                     * not after the last day of the displayed period, and not after the last instance defined by UNTIL or COUNT*Frequency*INTERVAL
                     * BY: only parse BYDAY, BYMONTH, BYMONTHDAY, possibly with multiple instances (eg BYDAY=TU,WE or BYMONTHDAY=1,2,3,4,5)
                     * not parsed: BYYEARDAY, BYSETPOS, BYHOUR, BYMINUTE, WKST
                     * examples:
                     * FREQ=MONTHLY;UNTIL=20201108T225959Z;BYMONTHDAY=8 Every 8th of the month until 20201108
                     * FREQ=MONTHLY;UNTIL=20201010T215959Z;BYDAY=2SA Monthly  2nde saturday until 20201010.
                     * FREQ=MONTHLY;BYMONTHDAY=5 Monthly the 5th
                     * FREQ=WEEKLY;INTERVAL=3;BYDAY=SU,SA Every 3 weeks on sunday and saturday
                     * FREQ=WEEKLY;COUNT=10;BYDAY=MO,TU,WE,TH,FR Every week 10 times on weekdays
                     * FREQ=YEARLY;BYMONTH=10;BYDAY=-1SU every year last sunday of october
                     * FREQ=DAILY;COUNT=5;INTERVAL=7 Every 7 days,5 times
                     
                     */
                    $timezone = new \DateTimeZone((isset($e->tzid)&& $e->tzid !== '') ? $e->tzid : $this->timezone_string);
                    $edtstart = new \DateTime('@' . $e->start);
                    $edtstart->setTimezone($timezone);
                    $edtstartmday = $edtstart->format('j');
                    $edtstarttod = $edtstart->format('His');
                    $edtstarthour = (int) $edtstart->format('H');
                    $edtstartmin = (int) $edtstart->format('i');
                    $edtstartsec = (int) $edtstart->format('s');
                    $edtendd   = new \DateTime('@' . $e->end);
                    $edtendd->setTimezone($timezone);
                    $edurationsecs =  $e->end - $e->start;
                    
                    $rrules = array();
                    $rruleStrings = explode(';', $e->rrule);
                    foreach ($rruleStrings as $s) {
                        list($k, $v) = explode('=', $s);
                        $rrules[strtolower ($k)] = strtoupper ($v);
                    }
                    // Get frequency and other values when set
                    $frequency = $rrules['freq'];
                    $interval = (isset($rrules['interval']) && $rrules['interval'] !== '') ? $rrules['interval'] : 1;
                    $freqinterval =new \DateInterval('P' . $interval . substr($frequency,0,1));
                    $interval3day =new \DateInterval('P3D');
                    $until = (isset($rrules['until'])) ? $this->parseIcsDateTime($rrules['until']) : $penddate;
                    $until = ($until < $penddate) ? $until : ($penddate - 1);
                    $freqendloop = ($until > $penddate) ? $until : $penddate;
                    switch ($frequency){
                        case "YEARLY"	:
                            $freqendloop = $freqendloop + (31622400 * $interval); // 366 days in sec
                            break;
                        case "MONTHLY"	:
                            $freqendloop = $freqendloop + (2678400 * $interval); // 31 days in sec
                            break;
                            
                        case "WEEKLY"	:
                            $freqendloop = $freqendloop + (604800 * $interval); // 7 days in sec
                            break;
                            
                        case "DAILY"	:
                            $freqendloop = $freqendloop + (86400 * $interval); // 1 days in sec
                            break;
                            
                    }
                    $count = (isset($rrules['count'])) ? $rrules['count'] : 0;
                    $bymonth = explode(',', (isset($rrules['bymonth'])) ? $rrules['bymonth'] : '');
                    $bymonthday = explode(',', (isset($rrules['bymonthday'])) ? $rrules['bymonthday'] : '');
                    $byday = explode(',', (isset($rrules['byday'])) ? $rrules['byday'] : '');
                    $i = 1;
                    $cen = 0;
                    switch ($frequency){
                        case "YEARLY"	:
                        case "MONTHLY"	:
                        case "WEEKLY"	:
                        case "DAILY"	:
                            $fmdayok = true;
                            $freqstart = clone $edtstart;
                            $newstart = clone $edtstart;
                            while ( $freqstart->getTimestamp() <= $freqendloop
                                && ($count == 0 || $i < $count  )            						)
                            {   // first FREQ loop on dtstart will only output new events
                                // created by a BY... clause
                                $test = '';
                                //							$test = print_r($e->exdate, true);
                                $fd = $freqstart->format('d'); // Day of the month, 2 digits with leading zeros
                                $fn = $freqstart->format('n'); // Month, without leading zeros
                                $fY = $freqstart->format('Y'); // Year, 4 digits
                                $fH = $freqstart->format('H'); // 24-hour format of an hour with leading zeros
                                $fi = $freqstart->format('i'); // Minutes with leading zeros
                                $fdays = $freqstart->format('t'); // Number of days in the given month
                                $expand = false;
                                // bymonth
                                if (isset($rrules['bymonth'])) {
                                    $bym = array();
                                    foreach ($bymonth as $by){
                                        // convert bymonth ordinals to month-numbers
                                        if ($by < 0){
                                            $by = 13 + $by;
                                        }
                                        $bym[] = $by;
                                    }
                                    $bym= array_unique($bym); // make unique
                                    sort($bym);	// order array so that oldest items first are counted
                                } else {$bym= array('');}
                                foreach ($bym as $by) {
                                    $newstart->setTimestamp($freqstart->getTimestamp()) ;
                                    if (isset($rrules['bymonth'])){
                                        
                                        if ($frequency == 'YEARLY' ){ // expand
                                            $newstart->setDate($fY , $by, 1);
                                            $ndays = intval($newstart->format('t'));
                                            $expand = true;
                                            if (intval($fd) <= $ndays) {
                                                $newstart->setDate($fY , $by, $fd);
                                            } elseif (isset($rrules['bymonthday'])
                                                || isset($rrules['byday'])){
                                                    // no action day-of the-month is set later
                                            }  else {
                                                continue;
                                            }
                                        } else
                                        { // limit
                                            if ((!$fmdayok) ||
                                                (intval($newstart->format('n')) != intval($by)))
                                            {continue;}
                                        }
                                    } else { // passthrough
                                    }
                                    // bymonthday
                                    if (isset($rrules['bymonthday'])) {
                                        $byn = array();
                                        $ndays = intval($newstart->format('t'));
                                        foreach ($bymonthday as $by){
                                            // convert bymonthday ordinals to day-of-month-numbers
                                            if ($by < 0){
                                                $by = 1 + $ndays + intval($by);
                                            }
                                            if ($by > 0 && $by <= $ndays) {
                                                $byn[] = $by;
                                            }
                                        }
                                        $byn= array_unique($byn); // make unique
                                        sort($byn);	// order array so that oldest items first are counted
                                    } else {$byn = array('');}
                                    
                                    foreach ($byn as $by) {
                                        if (isset($rrules['bymonthday'])){
                                            if (in_array($frequency , array('MONTHLY', 'YEARLY')) ){ // expand
                                                $expand = true;
                                                $newstart->setDate($newstart->format('Y'), $newstart->format('m'), $by);
                                            } else
                                            { // limit
                                                if ((!$fmdayok) ||
                                                    (intval($newstart->format('j')) !== intval($by)))
                                                {continue;}
                                            }
                                        } else { // passthrough
                                        }
                                        // byday
                                        $bydays = array();
                                        if (isset($rrules['byday'])){
                                            if (in_array($frequency , array('WEEKLY','MONTHLY', 'YEARLY'))
                                                && (! isset($rrules['bymonthday']))
                                                && (! isset($rrules['byyearday']))) { // expand
                                                    $expand =true;
                                                    foreach ($byday as $by) {
                                                        // expand byday codes to bydays datetimes
                                                        $byd = self::$weekdays[substr($by,-2)];
                                                        if (!($byd > 'a')) continue; // if $by contains only number (not good ical)
                                                        $byi = intval($by);
                                                        $wdf = clone $newstart;
                                                        if ($frequency == 'MONTHLY'	|| $frequency == 'YEARLY' ){
                                                            $wdl = clone $newstart;
                                                            if ($frequency == 'YEARLY' && (!isset($rrules['bymonth']))){
                                                                $wdf->setDate($fY , 1, $fd);
                                                                $wdl->setDate($fY , 12, $fd);
                                                            }
                                                            $wdf->modify('first ' . $byd . ' of');
                                                            $wdl->modify('last ' . $byd . ' of');
                                                            $wdf->setTime($fH, $fi);
                                                            $wdl->setTime($fH, $fi);
                                                            if ($byi > 0) {
                                                                $wdf->add(new \DateInterval('P' . ($byi - 1) . 'W'));
                                                                $bydays[] = $wdf->getTimestamp();
                                                            } elseif ($byi < 0) {
                                                                $wdl->sub(new \DateInterval('P' . (- $byi - 1) . 'W'));
                                                                $bydays[] = $wdl->getTimestamp();
                                                                
                                                            }
                                                            else {
                                                                while ($wdf <= $wdl) {
                                                                    $bydays[] = $wdf->getTimestamp();
                                                                    $wdf->add(new \DateInterval('P1W'));
                                                                }
                                                            }
                                                        } // Yearly or Monthly
                                                        else  { // $frequency == 'WEEKLY' byi is not allowed so we dont parse it
                                                            $wdnrn = $newstart->format('N'); // Mo 1; Su 7
                                                            $wdnrb = array_search($byd,array_values(self::$weekdays)) + 1;  // numeric index in weekdays
                                                            if ($wdnrb > $wdnrn) {
                                                                $wdf->add (new \DateInterval('P' . ($wdnrb - $wdnrn ) . 'D'));
                                                            }
                                                            if ($wdnrb < $wdnrn) {
                                                                $wdf->sub (new \DateInterval('P' . ($wdnrn - $wdnrb) . 'D'));
                                                                
                                                            }
                                                            $bydays[] = $wdf->getTimestamp();
                                                            
                                                        } // Weekly
                                                        
                                                    } // foreach
                                            } // expand
                                            else { // limit frequency period smaller than Week//
                                                // intval (byi) is not allowed with a frquency other than YEARLY or MONTHLY so
                                                // RRULE:FREQ=DAILY;BYDAY=-1SU; won't give any reptition.
                                                if ($byday == array('')
                                                    || in_array(strtoupper(substr($newstart->format('D'),0,2 )), $byday)
                                                    ){ // only one time in this loop no change of $newstart
                                                        $bydays =  array('');
                                                } else {
                                                    continue;
                                                }
                                            } // limit
                                        } // isset byday
                                        else {$bydays = array('');
                                        }
                                        $bydays= array_unique($bydays); // make unique
                                        sort($bydays);	// order array so that oldest items first are counted
                                        foreach ($bydays as $by) {
                                            if (intval($by) > 0 ) {
                                                $newstart->setTimestamp($by) ;
                                            }
                                            if (
                                                ($fmdayok  || $expand
                                                    || $newstart->format('Ymd') != $edtstart->format('Ymd'))
                                                && ($count == 0 || $i < $count)
                                                && $newstart->getTimestamp() < $until
                                                && !(!empty($e->exdate) && in_array($newstart->getTimestamp(), $e->exdate))
                                                && $newstart> $edtstart) { // count events after dtstart
                                                    if (($newstart->getTimestamp() + $edurationsecs) >= $this->now
                                                        ) { // copy only events after now
                                                            $cen++;
                                                            $en =  clone $e;
                                                            $en->start = $newstart->getTimestamp();
                                                            $en->end = $en->start + $edurationsecs;
                                                            if ($en->startisdate ){ //
                                                                $endtime = date('His', $en->end, $timezone);
                                                                if ('000000' < $endtime){
                                                                    if ('120000' < $endtime) $en->end = $en->end + 86400;
                                                                    $enddate = \DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d 00:00:00', $en->end, $timezone), $timezone );
                                                                    $en->end = $enddate->getTimestamp();
                                                                }
                                                            }
                                                            $en->uid = $i . '_' . $e->uid;
                                                            if ($test > ' ') { 	$en->summary = $en->summary . '<br>Test:' . $test; 	}
                                                            $events[] = $en;
                                                    } // copy eevents
                                                    // next eventcount from $e->start
                                                    $i++;
                                            } // end count events
                                        } // end byday
                                    } // end bymonthday
                                } // end bymonth
                                // next startdate by FREQ for loop < $until and <= $penddate
                                $freqstart->add($freqinterval);
                                if ($freqstart->format('His') != $edtstarttod) {// correction when time changed by ST to DST transition
                                    $freqstart->setTime($edtstarthour, $edtstartmin, $edtstartsec);
                                }
                                if  ($fmdayok &&
                                    in_array($frequency , array('MONTHLY', 'YEARLY')) &&
                                    $freqstart->format('j') !== $edtstartmday){ // monthday changed eg 31 jan + 1 month = 3 mar; 
                                        $freqstart->sub($interval3day);
                                        $fmdayok = false;
                                } elseif (!$fmdayok ){
                                    $freqstart->add($interval3day);
                                    $fmdayok = true;
                                    
                                }
                            }
                    }
                } // switch freq
                //
                $parsedUntil = strpos($curstr, self::TOKEN_END_VEVENT) + strlen(self::TOKEN_END_VEVENT) + 1;
                $curstr = substr($curstr, $parsedUntil);
            } else {
                $haveVevent = false;
            }
        } while($haveVevent);
        
        usort($events, array($this, "eventSortComparer"));
        
        $this->events = $events;
    }
    
    public function getFutureEvents($penddate ) {
        // events are already sorted
        $newEvents = array();
//        $this->now = time();
        
        foreach ($this->events as $e) {
            if ((($e->start > $this->now) || (!empty($e->end) && $e->end >= $this->now))
                && $e->start <= $penddate) {
                    $newEvents[] = $e;
                }
        }
        
        return $newEvents;
    }
    
    public function getAll() {
        return $this->events;
    }
    /*
    * Parse timestamp from date time string (with timezone ID)
    * @param  string $datetime date time format YYYYMMDDTHHMMSSZ last letter ='Z' means Zero-time or 'UTC' time. overrides any timezone.
    * @param  string $ptzid (timezone ID)
    * @return \DateTimeZone object
    */
    
    private function parseIcsDateTime($datetime, $tzid = '') {
        if (strlen($datetime) < 8) {
            return -1;
        }
        
        if (strlen($datetime) >= 13)  {
            $hms = substr($datetime, 9, 4) . '00';
        } else {
            $hms = '000000';
        }
        
        // check if it is GMT
        $lastChar = $datetime[strlen($datetime) - 1];
        if ($lastChar == 'Z') {
            $tzid = 'UTC';
        } else  {
            $tzid = $this->parseIanaTimezoneid ($tzid)->getName();
        }
        $date = \DateTime::createFromFormat('Ymd His e', substr($datetime,0,8) . ' ' . $hms. ' ' . $tzid);
        $timestamp = $date->getTimestamp();
        return $timestamp;
    }
    /**
     * Checks if a time zone is a recognised Windows (non-CLDR) time zone
     *
     * @param  string $timeZone
     * @return boolean
     */
    public function isValidWindowsTimeZoneId($timeZone)
    {
        return array_key_exists(html_entity_decode($timeZone), self::$windowsTimeZonesMap);
    }
    /**
     * Checks if Zero time (timezone UTC)
     * Checks if a time zone ID is a Iana timezone then return this timezone.
     * If empty return timezone from WP
     * Checks if time zone ID is windows timezone then return this timezone
     * If nothing istrue return timezone from WP
     * If timezone string from WP doesn't make a good timezone return UTC timezone.
     *
     * @param  string $ptzid (timezone ID)
     * @param  string $datetime date time with format YYYYMMDDTHHMMSSZ last letter ='Z' means Zero-time (='UTC' time).
     * @return \DateTimeZone object
     */
    
    private function parseIanaTimezoneid ($ptzid = '', $datetime = '') {
        if (8 < strlen($datetime) && 'Z'== $datetime[strlen($datetime) - 1]) $ptzid = 'UTC';
        try {
            $timezone = (isset($ptzid)&& $ptzid !== '') ? new \DateTimeZone($ptzid) : new \DateTimeZone($this->timezone_string);
        } catch (\Exception $exc) {}
        if (isset($timezone)) return $timezone;
        try {
            if (isset(self::$windowsTimeZonesMap[$ptzid])) $timezone = new \DateTimeZone(self::$windowsTimeZonesMap[$ptzid]);
        } catch (\Exception $exc) {}
        if (isset($timezone)) return $timezone;
        try {
            $timezone = new \DateTimeZone($this->timezone_string);
        } catch (\Exception $exc) { }
        if (isset($timezone)) return $timezone;
        return new \DateTimeZone('UTC');
    }
    
    private function eventSortComparer($a, $b) {
        if ($a->start == $b->start) {
            return 0;
        } else if($a->start > $b->start) {
            return 1;
        } else {
            return -1;
        }
    }
    /**
     * Parse an event string from an ical file to an event object.
     *
     * @param  string $eventStr
     * @param  array  $instance array of options.
     *    ['allowhtml'] allow html in output.
     * @return \StdClass $eventObj
     */
    public function parseVevent($eventStr, $instance) {
        $lines = explode("\n", $eventStr);
        $eventObj = new \StdClass;
        $tokenprev = "";
        
        foreach($lines as $l) {
            // trim() to remove \n\r\0 but not space to keep a clean line with any spaces at the beginning or end of the line
            $l =trim($l, "\n\r\0");
            $list = explode(":", $l, 2);
            $token = "";
            $value = "";
            $tzid = '';
            $isdate = false;
            //bw 20171108 added, because sometimes there is timezone or other info after DTSTART, or DTEND
            //     eg. DTSTART;TZID=Europe/Amsterdam, or  DTSTART;VALUE=DATE:20171203
            $tl = explode(";", $list[0]);
            $token = $tl[0];
            if (count($tl) > 1 ){
                $dtl = explode("=", $tl[1]);
                if (count($dtl) > 1 ){
                    switch($dtl[0]) {
                        case 'TZID':
                            $tzid = $dtl[1];
                            break;
                        case 'VALUE':
                            $isdate = (substr( $dtl[1],0,4) == 'DATE');
                            break;
                    }
                }
            }
            if (count($list) > 1 && strlen($token) > 1 && substr($token, 0, 1) > ' ') { //all tokens start with a alphabetic char , otherwise it is a continuation of a description with a colon in it.
                // trim() to remove \n\r\0
                $value = trim($list[1]);
                $desc = ( $instance['allowhtml']) ? $list[1] : htmlspecialchars($list[1]);
                $desc = str_replace(array('\;', '\,', '\r\n','\n', '\r'), array(';', ',', "\n","\n","\n"), $desc);
                $tokenprev = $token;
                switch($token) {
                    case "SUMMARY":
                        $eventObj->summary = $desc;
                        break;
                    case "DESCRIPTION":
                        $eventObj->description = $desc;
                        break;
                    case "LOCATION":
                        $eventObj->location = $desc;
                        break;
                    case "DTSTART":
                        $tz = $this->parseIanaTimezoneid ($tzid,$value);
                        $tzid = $tz->getName();
                        $eventObj->tzid = $tzid;
                        $eventObj->startisdate = $isdate;
                        $eventObj->start = $this->parseIcsDateTime($value, $tzid);
                        if (!isset($eventObj->end)) { // because I am not sure the order is alway DTSTART before DTEND
                            $eventObj->endisdate = $isdate;
                            $eventObj->end = $eventObj->start;
                        }
                        break;
                    case "DTEND":
                        $eventObj->endisdate = $isdate;
                        $eventObj->end = $this->parseIcsDateTime($value, $tzid);
                        break;
                    case "UID":
                        $eventObj->uid = $value;
                        break;
                    case "RRULE":
                        $eventObj->rrule = $value;
                        break;
                    case "EXDATE":
                        $dtl = explode(",", $value);
                        foreach ($dtl as $value) {
                            $eventObj->exdate[] = $this->parseIcsDateTime($value, $tzid);
                        }
                        break;
                }
            }else { // count($list) <= 1
                if (strlen($l) > 1) {
                    $desc = ($instance['allowhtml']) ? $l : htmlspecialchars($l);
                    $desc = str_replace(array('\;', '\,', '\r\n','\n', '\r'), array(';', ',', "\n","\n","\n"), substr($desc,1));
                    switch($tokenprev) {
                        case "SUMMARY":
                            $eventObj->summary .= $desc;
                            break;
                        case "DESCRIPTION":
                            $eventObj->description .= $desc;
                            break;
                        case "LOCATION":
                            $eventObj->location .= $desc;
                            break;
                    }
                }
            }
        }
        return $eventObj;
    }
    /**
     * Gets data from calender or transient cache
     *
     * @param array $instance the block attributes
     *    ['blockid'] to create transientid
     *    ['transient_time'] time the transient cache is valid in minutes.
     *    ['calendar_id'] id or url of the calender to fetch data
     *    ['event_count'] max number of events to return
     *    ['event_period'] max number of days after now to fetch events.
     *    ['allowhtml'] allow html in output.
     *
     * @return array event objects
     */
    function getData($instance)
    {
        $cacheId =  $instance['blockid']   ;
        $cachegroup = 'SimpleicalBlock';
        $options = array(
            'lifetime'     => (int) $instance['transient_time'], // seems to be minutes already, not saved, evaluated on get
            'caching'      => true,
            'language'     => 'en-GB',
            'application'  => 'site',
        );
        $cachecontroller = new OutputController($options);
        
        //        if ($instance['clear_cache_now']) $cachecontroller->cache->remove($cacheId, $cachegroup);
        if(false === ( $data = $cachecontroller->get( $cacheId, $cachegroup))) {
            $data = $this->fetch(  $instance,  );
            // do not cache data if fetching failed
            if ($data) {
                $cachecontroller->store($data, $cacheId, $cachegroup );
            }
        }
        return $data;
    }
    /**
     * Fetches from calender
     *
     * @param array $instance the block attributes
     *    ['calendar_id']  id or url of the calender to fetch data
     *    ['event_count']  max number of events to return
     *    ['event_period'] max number of days after now to fetch events.
     *    ['allowhtml'] allow html in output.
     *
     * @return array event objects
     */
    function fetch( $instance )
    {
        $period = $instance['event_period'];
        if ('#example' == $instance['calendar_id']){
            $httpBody = self::$example_events;
        }
        else  {
            $url = self::getCalendarUrl($instance['calendar_id']);
            $httpResponse =  $this->http->get($url);
            if ($httpResponse->code != 200) {
                echo '<!-- ' . $url . ' not found ' . 'fall back to https:// -->';
                $httpResponse =  $this->http('https://' . explode('://', $url)[1]);
                if ($httpResponse->code != 200) {
                    echo 'Simple iCal Block: ', $httpResponse->code;
                    return false;
                }
            }
            $httpBody = $httpResponse->body;
        }
        
//        if(!is_array($httpData) || !array_key_exists('body', $httpData)) {
//            return false;
//        }
        
        try {
            $penddate = strtotime("+$period day");
//            $parser = new IcsParser(); // is already instantiated before getData call in Joomla
            $this->parse($httpBody, $penddate, $instance['event_count'],  $instance );
            
            $events = $this->getFutureEvents($penddate);
            return self::limitArray($events, $instance['event_count']);
        } catch(\Exception $e) {
            return null;
        }
    }
    
    private static function getCalendarUrl($calId)
    {
        $protocol = strtolower(explode('://', $calId)[0]);
        if (array_search($protocol, array('http', 'https', 'webcal')))
        { return $calId; }
        else
        { return 'https://www.google.com/calendar/ical/'.$calId.'/public/basic.ics'; }
    }
    
    private static function limitArray($arr, $limit)
    {
        $i = 0;
        $out = array();
        foreach ($arr as $e) {
            $i++;
            if ($i > $limit) {
                break;
            }
            $out[] = $e;
        }
        return $out;
    }
    
}