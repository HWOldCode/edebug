# edebug
Debug helping in EGroupware UI with implemented xhprof, analyse your code and see time of exec.

List view call URI and APP, with contextmenu you can see xhprof table and graph.

Xhprof Data write in VFS of EGroupware /app/edebug/{unid}/{unid}.xhprof

This project was created for my other project -> ewawi, elogin and eworkflow

# License
http://opensource.org/licenses/GPL-2.0 GPL2 - GNU General Public License, version 2 (GPL-2.0)

# Howto use

1. Install XHProf @see http://pecl.php.net/package/xhprof
2. Copy /src/ to egroupware/edebug
3. Setup EGroupware Applications and install edebug
4. Add access edebug application to user or group in admin interface
5. Go to settings and enable XHProf
6. Add Lines to your code:

- Begin profiling:
```php
if( $GLOBALS['egw_info']['user']['apps']['edebug'] ) {
	edebug_bo::xhprof_enable();
}
```

- End profiling:
```php
if( $GLOBALS['egw_info']['user']['apps']['edebug'] ) {
	edebug_bo::xhprof_disable();
}
```

# Screens

<p align="center">
  <img src="/doc/images/edebugsettings.png" width="350"/><br>
  <img src="/doc/images/edebuglist.png" width="350"/><br>
  <img src="/doc/images/edebugtable.png" width="350"/><br>
  <img src="/doc/images/edebuggraph.png" width="350"/><br>
  <img src="/doc/images/edebugvfs.png" width="350"/><br>
</p>

# Helping the Project
Add new code or report bugs.

# Contact
If you have questions please ask.

Stefan