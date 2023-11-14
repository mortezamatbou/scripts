main :: IO()
main = do
    let a = 10
    if a > 10
        then putStr "a > 10"
    else putStr "a <= 10"