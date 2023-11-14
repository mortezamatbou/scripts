#!/bin/bash

i=1

while [[ $i -le 10 ]]; do
  echo "$i"
  (( i += 1 ))
done

for j in {1..5};do
  echo "$j"
done


if [ 11 -gt 10 ];then
  echo "Aaaa"
fi

n=1
if [ $n -lt 10 ];then
  echo "< 10"
elif [ $n -gt 10 ]; then
  echo "> 10"
fi
