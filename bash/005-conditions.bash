#!/bin/bash

# echo "Please enter a number:"
# read num

echo "Your number is $1"

num=$1

if [ $num -gt 0 ]; then
    echo "$num is positive"
elif [ $num -lt 0 ]; then
    echo "$num is negative"
else
    echo "$num is zero"
fi
