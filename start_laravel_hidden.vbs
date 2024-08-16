Set WshShell = CreateObject("WScript.Shell")
WshShell.Run chr(34) & "C:\wamp64\www\cantinecbox\start_laravel_server.bat" & Chr(34), 0
Set WshShell = Nothing