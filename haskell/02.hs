f :: Integer -> Integer -> Integer
f a b = a + b

f2 :: Integer -> Integer -> Integer
f2 x y = f x y + f x y

main :: IO()
main = do
    let a = f2 10 20
    print(a)