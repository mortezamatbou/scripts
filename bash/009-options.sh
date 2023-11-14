version="Lobdown Safe Capital Investment 1.25.5"

name=""
family=""

while [[ "$1" =~ ^- && ! "$1" == "--" ]]; do
    case $1 in
        -V | --version)
            echo "$version"
            exit
            ;;
        -fn | --name)
            shift; name=$1
            ;;
        -ln | --family)
            shift; family=$1
            ;;
        -f | --flag)
            echo "Your flag is up"
            exit
            ;;
    esac; shift;
done

printf "First Name:%s \nLast Name:%s %d\n" $name $family 10
echo $0
echo $_
echo $$
echo $!
echo $?
# if [[ "$1" == '--' ]]; then shift; fi