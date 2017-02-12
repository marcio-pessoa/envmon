v 20130925 2
C 40000 40000 0 0 0 title-B.sym
C 48500 46300 1 180 1 diode-1.sym
{
T 48900 45700 5 10 0 0 180 6 1
device=DIODE
T 49400 46200 5 10 1 1 0 6 1
refdes=D1
T 49300 45700 5 10 1 1 0 6 1
value=1N4001
}
C 46700 45400 1 0 0 resistor-1.sym
{
T 47000 45800 5 10 0 0 0 0 1
device=RESISTOR
T 47200 45700 5 10 1 1 0 0 1
refdes=R1
T 46900 45200 5 10 1 1 0 0 1
value=270 R
}
C 45800 45400 1 0 0 input-1.sym
{
T 45800 45700 5 10 0 0 0 0 1
device=INPUT
T 45400 45700 5 10 1 1 0 0 1
value=Arduino PWM pin
}
C 49400 46500 1 90 0 dc_motor-1.sym
{
T 48400 46900 5 10 0 0 90 0 1
device=DC_MOTOR
T 49300 47000 5 10 1 1 180 0 1
refdes=M1
}
C 48200 44300 1 0 0 gnd-1.sym
N 48300 44600 48300 45000 4
N 47600 45500 47700 45500 4
N 46700 45500 46600 45500 4
C 51200 45100 1 0 1 lm7805-1.sym
{
T 49600 46400 5 10 0 0 0 6 1
device=7805
T 50700 46100 5 10 1 1 0 0 1
refdes=U1
}
C 51100 44700 1 270 1 capacitor-1.sym
{
T 51800 44900 5 10 0 0 90 2 1
device=CAPACITOR
T 51400 45400 5 10 1 1 180 6 1
refdes=C2
T 51400 44900 5 10 1 1 0 0 1
value=100nF
}
C 49300 44700 1 270 1 capacitor-1.sym
{
T 50000 44900 5 10 0 0 90 2 1
device=CAPACITOR
T 49600 45400 5 10 1 1 180 6 1
refdes=C1
T 49600 44900 5 10 1 1 0 0 1
value=100nF
}
C 51400 44300 1 0 1 gnd-1.sym
C 51500 45800 1 0 1 vcc-2.sym
{
T 51700 46200 5 10 1 1 180 0 1
value=12V
}
C 50500 44300 1 0 1 gnd-1.sym
C 49600 44300 1 0 1 gnd-1.sym
N 51300 44700 51300 44600 4
N 50400 45100 50400 44600 4
N 49500 44700 49500 44600 4
N 51200 45700 51300 45700 4
N 51300 45600 51300 45800 4
N 49600 45700 49500 45700 4
C 47700 45000 1 0 0 npn-3.sym
{
T 48600 45500 5 10 0 0 0 0 1
device=NPN_TRANSISTOR
T 47900 45900 5 10 1 1 0 0 1
refdes=Q1
T 47600 45000 5 10 1 1 0 0 1
value=BC547
}
N 49500 45600 49500 46700 4
N 49500 46700 49400 46700 4
N 48400 46700 48300 46700 4
N 48300 46700 48300 46000 4
N 48300 46100 48500 46100 4
N 49400 46100 49500 46100 4