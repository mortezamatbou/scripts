#!/bin/bash

name="Morteza Matbou"
name="Matbou"
name="Another Name"
name="Morteza"
name="Mori"

case $name in
    "Morteza" | "Mori")
        echo "Name"
        ;;
    "Morteza Matbou")
        echo "Name Family"
        ;;
    "Matbou")
        echo "Family"
        ;;
    *)
        echo "Undefined"
        ;;
esac
