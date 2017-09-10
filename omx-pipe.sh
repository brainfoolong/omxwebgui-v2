#!/bin/bash
# pipe commands to fifo file

echo -n "$2" > $1 &