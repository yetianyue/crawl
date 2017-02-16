#!/usr/bin/expect --
set timeout -1
if { [llength $argv] < 3} {
        puts "usage: $argv0 srcfile destfile password"
        exit 1
}

set timeout 300
set srcfile  [lindex $argv 0]
set destfile [lindex $argv 1]
set password [lindex $argv 2]

#spawn scp -r $srcfile $destfile
spawn scp -r $srcfile $destfile

expect {
        "password:" {
                send "$password"
                send "\r"
                expect "#"
        }

        "(yes/no)?" {
                send "yes\r"
                expect "password:" {
                        send "$password"
                        send "\r"
                        expect "#"
                }
        }

        timeout {
                exit 1
        }
}

