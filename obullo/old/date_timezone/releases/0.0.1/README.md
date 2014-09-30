## Date_Timezone Class

------

The Date Timezone Class contains functions that help you work with timezone dates.

### Initializing the Class

------

```php
new Date_Timezone;
$this->date_timezone->method();
```

The following functions are available:

#### $this->date_timezone->getMenu()

Generates a pull-down menu of timezones, like this one:

```php
<select name="timezones"> 
<option value="UM12">(UTC -12:00) Baker/Howland Island</option> 
<option value="UM11">(UTC -11:00) Samoa Time Zone, Niue</option> 
<option value="UM10">(UTC -10:00) Hawaii-Aleutian Standard Time, Cook Islands, Tahiti</option> 
<option value="UM95">(UTC -9:30) Marquesas Islands</option> 
<option value="UM9">(UTC -9:00) Alaska Standard Time, Gambier Islands</option> 
<option value="UM8">(UTC -8:00) Pacific Standard Time, Clipperton Island</option> 
<option value="UM7">(UTC -7:00) Mountain Standard Time</option> 
<option value="UM6">(UTC -6:00) Central Standard Time</option> 
<option value="UM5">(UTC -5:00) Eastern Standard Time, Western Caribbean Standard Time</option> 
<option value="UM45">(UTC -4:30) Venezuelan Standard Time</option> 
<option value="UM4">(UTC -4:00) Atlantic Standard Time, Eastern Caribbean Standard Time</option> 
<option value="UM35">(UTC -3:30) Newfoundland Standard Time</option> 
<option value="UM3">(UTC -3:00) Argentina, Brazil, French Guiana, Uruguay</option> 
<option value="UM2">(UTC -2:00) South Georgia/South Sandwich Islands</option> 
<option value="UM1">(UTC -1:00) Azores, Cape Verde Islands</option> 
<option value="UTC" selected="selected">(UTC) Greenwich Mean Time, Western European Time</option> 
<option value="UP1">(UTC +1:00) Central European Time, West Africa Time</option> 
<option value="UP2">(UTC +2:00) Central Africa Time, Eastern European Time, Kaliningrad Time</option> 
<option value="UP3">(UTC +3:00) Moscow Time, East Africa Time</option> 
<option value="UP35">(UTC +3:30) Iran Standard Time</option> 
<option value="UP4">(UTC +4:00) Azerbaijan Standard Time, Samara Time</option> 
<option value="UP45">(UTC +4:30) Afghanistan</option> 
<option value="UP5">(UTC +5:00) Pakistan Standard Time, Yekaterinburg Time</option> 
<option value="UP55">(UTC +5:30) Indian Standard Time, Sri Lanka Time</option> 
<option value="UP575">(UTC +5:45) Nepal Time</option> 
<option value="UP6">(UTC +6:00) Bangladesh Standard Time, Bhutan Time, Omsk Time</option> 
<option value="UP65">(UTC +6:30) Cocos Islands, Myanmar</option> 
<option value="UP7">(UTC +7:00) Krasnoyarsk Time, Cambodia, Laos, Thailand, Vietnam</option> 
<option value="UP8">(UTC +8:00) Australian Western Standard Time, Beijing Time, Irkutsk Time</option> 
<option value="UP875">(UTC +8:45) Australian Central Western Standard Time</option> 
<option value="UP9">(UTC +9:00) Japan Standard Time, Korea Standard Time, Yakutsk Time</option> 
<option value="UP95">(UTC +9:30) Australian Central Standard Time</option> 
<option value="UP10">(UTC +10:00) Australian Eastern Standard Time, Vladivostok Time</option> 
<option value="UP105">(UTC +10:30) Lord Howe Island</option> 
<option value="UP11">(UTC +11:00) Magadan Time, Solomon Islands, Vanuatu</option> 
<option value="UP115">(UTC +11:30) Norfolk Island</option> 
<option value="UP12">(UTC +12:00) Fiji, Gilbert Islands, Kamchatka Time, New Zealand Standard Time</option> 
<option value="UP1275">(UTC +12:45) Chatham Islands Standard Time</option> 
<option value="UP13">(UTC +13:00) Phoenix Islands Time, Tonga</option> 
<option value="UP14">(UTC +14:00) Line Islands</option> 
</select>
```

This menu is useful if you run a membership site in which your users are allowed to set their local timezone value.

The first parameter lets you set the "selected" state of the menu. For example, to set Pacific time by default you will do this:

```php
echo $this->date_timezone->getZones('UM8');
```

Please see the timezone reference below to see the values of this menu.

The second parameter lets you set a CSS class name for the menu.

**Note:** The text contained in the menu is found in the following language file: *lang//date.php*

### Timezone Reference

------

The following table indicates each timezone and its location. More details at http://en.wikipedia.org/wiki/Time_zone#UTC

<table>
<thead>
<tr>
<th>Time Zone</th><th>Location</th></tr>
<tbody>
<tr><td>UM12</td><td>(UTC - 12:00) Enitwetok, Kwajalien</td></tr>
<tr><td>UM11</td><td>(UTC - 11:00) Nome, Midway Island, Samoa</td></tr>
<tr><td>UM10</td><td>(UTC - 10:00) Hawaii</td></tr>
<tr><td>UM9</td><td>(UTC - 9:00) Alaska</td></tr>
<tr><td>UM8</td><td>(UTC - 8:00) Pacific Time</td></tr>
<tr><td>UM7</td><td>(UTC - 7:00) Mountain Time</td></tr>
<tr><td>UM6</td><td>(UTC - 6:00) Central Time, Mexico City</td></tr>
<tr><td>UM5</td><td>(UTC - 5:00) Eastern Time, Bogota, Lima, Quito</td></tr>
<tr><td>UM4</td><td>(UTC - 4:00) Atlantic Time, Caracas, La Paz</td></tr>
<tr><td>UM25</td><td>(UTC - 3:30) Newfoundland</td></tr>
<tr><td>UM3</td><td>(UTC - 3:00) Brazil, Buenos Aires, Georgetown, Falkland Is.</td></tr>
<tr><td>UM2</td><td>(UTC - 2:00) Mid-Atlantic, Ascention Is., St Helena</td></tr>
<tr><td>UM1</td><td>(UTC - 1:00) Azores, Cape Verde Islands</td></tr>
<tr><td>UTC</td><td>(UTC) Casablanca, Dublin, Edinburgh, London, Lisbon, Monrovia</td></tr>
<tr><td>UP1</td><td>(UTC + 1:00) Berlin, Brussels, Copenhagen, Madrid, Paris, Rome</td></tr>
<tr><td>UP2</td><td>(UTC + 2:00) Istanbul, Kaliningrad, South Africa, Warsaw</td></tr>
<tr><td>UP3</td><td>(UTC + 3:00) Baghdad, Riyadh, Moscow, Nairobi</td></tr>
<tr><td>UP25</td><td>(UTC + 3:30) Tehran</td></tr>
<tr><td>UP4</td><td>(UTC + 4:00) Adu Dhabi, Baku, Muscat, Tbilisi</td></tr>
<tr><td>UP35</td><td>(UTC + 4:30) Kabul</td></tr>
<tr><td>UP5</td><td>(UTC + 5:00) Islamabad, Karachi, Tashkent</td></tr>
<tr><td>UP45</td><td>(UTC + 5:30) Bombay, Calcutta, Madras, New Delhi</td></tr>
<tr><td>UP6</td><td>(UTC + 6:00) Almaty, Colomba, Dhaka</td></tr>
<tr><td>UP7</td><td>(UTC + 7:00) Bangkok, Hanoi, Jakarta</td></tr>
<tr><td>UP8</td><td>(UTC + 8:00) Beijing, Hong Kong, Perth, Singapore, Taipei</td></tr>
<tr><td>UP9</td><td>(UTC + 9:00) Osaka, Sapporo, Seoul, Tokyo, Yakutsk</td></tr>
<tr><td>UP85</td><td>(UTC + 9:30) Adelaide, Darwin</td></tr>
<tr><td>UP10</td><td>(UTC + 10:00) Melbourne, Papua New Guinea, Sydney, Vladivostok</td></tr>
<tr><td>UP11</td><td>(UTC + 11:00) Magadan, New Caledonia, Solomon Islands</td></tr>
<tr><td>UP12</td><td>(UTC + 12:00) Auckland, Wellington, Fiji, Marshall Island</td></tr>
</tbody>
</table>

**Note:** The text generated by this function is found in the following language file: <kbd>app/translations/en_US/date_format.php</kbd>