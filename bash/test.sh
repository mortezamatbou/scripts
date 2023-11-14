#!/bin/bash


directories=`ls`

o=`ls | grep testaa`
# echo $o

# if [[ `ls | grep "testaa"` ]]; then
#     echo 'testaa is exists'
# fi

# num=$(( $1 + $2 ))
# echo $num
# declare -i count
# echo $count
# count+=1
# echo $count

# c=1
# echo $c
# c=$(( c + 1 ))
# echo $c

# j=1
# declare -i j=5
# while [[ j -le 10 ]]; do
#     echo "$j"
#     # (( j += 1 ))
#     j+=1
# done

# dir=${0%/*}
# echo $dir

# version="Lobdown Safe Capital Investment 1.25.5"

# string=""
# flag=0

# while [[ "$1" =~ ^- && ! "$1" == "--" ]]; do case $1 in
#   -V | --version )
#     echo "$version"
#     exit
#     ;;
#   -s | --string )
#     shift; string=$1
#     ;;
#   -f | --flag )
#     flag=1
#     ;;
# esac; shift; done
# if [[ "$1" == '--' ]]; then shift; fi

# echo -n "Proceed? [y/n]: "
# read -r ans
# echo "$ans"

# echo -n "Enter a name: "
# read name
# echo $name

# n=$(( 10 + 10 ))
# echo $n

(
    cd testdir
    echo "I'm now in $PWD"
)
pwd # still in first directory
