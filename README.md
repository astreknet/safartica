# sirius, safari class
Minimalistic **safari class** and **accident report** web application. Ask for a _testing account_ at the contact mail on the [site](https://sirius.astrek.net) 

<p align="center">
  <img src="img/example.webp" width=81% />
</p>

## users
Just an **email** is needed to create a user. Invitation to _register_ by _email_. Only the user can **edit his own data**. An _unregistered_ user will be deleted after **one day**. A **validated user** will be automatically logged out after **3 min** of **inactivity**. 

### userlevels
* **inactive**: _limbo_ status, can not log in.
* **guide**: create and update his own data, issues, trips, close calls and accidents.
* **admin**: same privileges as _guide_. Also can create and modify safaris, upgrade userlevels, download [_vcards_](https://en.wikipedia.org/wiki/VCard) and [CSV](https://en.wikipedia.org/wiki/Comma-separated_values) reports.
* **superadmin**: same privileges as _admin_. Also can create and modify admins.

## safaris
This are the _templates_ of the _trips_. A safari has a **unique name**, a duration and when _active_ is _available_ for the _trips_.

## trips
An _active user_ can _add_ and _update_ trips. A trip has a _safari name_, _time_ and a _route_. _Remarks_ can be added later. A trip can have _near misses_ and _accidents_.

## issues
Work issues are _near misses_ or _accidents_ (if there is an  _injury_), out of a _safari_, during the work time.

## reports
The admins can download trip, near miss, accident, work near miss and work accident reports in CSV format, [spreadsheets](https://en.wikipedia.org/wiki/Spreadsheet) suported by [Apple Numbers](https://en.wikipedia.org/wiki/Numbers_(spreadsheet)), [LibreOffice Calc](https://en.wikipedia.org/wiki/LibreOffice_Calc), and [Microsoft Excel](https://en.wikipedia.org/wiki/Microsoft_Excel) among others.

## built with
    
* [OpenBSD 7.3](https://www.openbsd.org)
* [PHP 8.2](https://www.php.net)
* [MariaDB 10.9](https://mariadb.com)
* [HTML5](https://html.spec.whatwg.org)
* [CSS3](https://www.w3.org/TR/CSS/#css)

## roadmap

* [x] automatic darkmode
* [x] responsive
* [x] manage users and userlevels (inactive, guide, admin)
* [x] registration and password recover by mail
* [x] guide contact vcards
* [x] create and activate safari templates
* [x] create and update personal trips
* [x] report accidents and close calls
* [x] work issues: accidents and near misses for the staff
* [x] download CSV reports
    - [x] trips
    - [x] trip near misses
    - [x] trip accidents
    - [x] work near misses
    - [x] work accidents
* [ ] add geolocation button to near misses and accidents 
* [ ] add 'days of the week' to safaris
* [ ] add pictures to the accident report
* [ ] add gear parts and prices (snowmobiles, bikes, skis, etc...)
* [ ] accident report pdf
* [ ] multi-language:
  - [x] english
  - [ ] spanish
  - [ ] finnish
* [ ] svg icons
  - [x] buttons
  - [ ] menu
