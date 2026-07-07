/* 
Lab 6: To evaluate the value of ∫₀^(π/2) √(sin x) dx 
Using Simpson's 1/3 Rule
*/

#include <stdio.h>
#include <conio.h>
#include <math.h>

#define y(x) (sqrt(sin(x)))   // input function

int main()
{
    float x0, xn, s, h, I;
    int i, n;

    // fixed limits as per question
    x0 = 0.0;
    xn = M_PI / 2;

    // input
    printf("Enter the number of sub intervals = ");
    scanf("%d", &n);

    if(n % 2 != 0)
    {
        printf("Error: n must be even for Simpson's 1/3 Rule.\n");
        return 0;
    }

    // Simpson's 1/3 rule
    h = (xn - x0) / n;
    s = y(x0) + y(xn);

    for(i = 1; i <= n - 1; i++)
    {
        if(i % 2 == 0)
            s = s + 2 * y(x0 + i * h);
        else
            s = s + 4 * y(x0 + i * h);
    }

    I = (h / 3) * s;

    // output
    printf("Simpson's 1/3 Rule Result = %f\n", I);

    return 0;
}
 