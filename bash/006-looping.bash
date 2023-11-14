#!/bin/bash

i=1

while [[ $i -le 10 ]];do
    echo "$i"
    (( i += 1 ))
done

echo "$i"

for j in {1..3};do
    echo "$j"
done

if [ 1 -gt 10 ];then
  echo "1 > 10"
fi


