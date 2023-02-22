---
author: Evan
date: 2022-11-17
title: Arduino CLI
---

[Arduino CLI](https://github.com/arduino/arduino-cli) is a command line tool that can replace the functionality of the graphical Arduino GUI.  

You can build, upload, use libraries, read the serial monitor of any board supported by Arduino in a headless setup, which means you can use your favorite editor for code editing and work remotely without relying on VNC or X-Forwarding.
    
## Example Build Script

**build.sh**

``` bash
#!/usr/bin/env bash

core=STMicroelectronics:stm32
board=Nucleo_144
board_options=upload_method=swdMethod,pnum=NUCLEO_L496ZG

function setup {
   arduino-cli core install STMicroelectronics:stm32 --additional-urls https://github.com/stm32duino/BoardManagerFiles/raw/main/package_stmicroelectronics_index.json 
}

function compile {
   arduino-cli compile . -b ${core}:${board} \
        --board-options ${board_options}
}

function upload {
    compile
    arduino-cli upload . -b ${core}:${board} \
        --board-options ${board_options}
}

function monitor {
    arduino-cli monitor -p /dev/ttyACM0 -c baudrate=115200
}

function details {
    arduino-cli board details -b ${core}:${board}
}

# easily run subcommands.  e.g. "./build upload"
"$@"

```

Then you can simply flash new code with

    ./build upload

## Connecting Monitor

    acli monitor -p /dev/ttyACM0 -c baudrate=115200

## Searching/Installing Additional Cores

Search default list of cores

    acli core search foo
    
Search cores on third-party URLs

    acli core search stm --additional-urls http://github.com/stm32duino/...
    
## Listing Available Boards or Board Options

List installed boards

    acli board listall
    
List available options for installed board

    acli board details -b STMicroelectronics:stm32:Nucleo_144
