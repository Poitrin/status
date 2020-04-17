CPU=`top -b -n1 | grep "Cpu(s)"` 
CPU_US=`echo $CPU | awk '{print $2}'`
CPU_SY=`echo $CPU | awk '{print $4}'`
FREE_DATA=`free -m | grep Mem` 
CURRENT_RAM=`echo $FREE_DATA | cut -f3 -d' '`
TOTAL_RAM=`echo $FREE_DATA | cut -f2 -d' '`
HDD=`df -lh | awk '{if ($6 == "/") { print $5 }}' | head -1 | cut -d'%' -f1`
curl --data "cpu_us=$CPU_US" --data "cpu_sy=$CPU_SY" --data "current_ram=$CURRENT_RAM" --data "total_ram=$TOTAL_RAM" --data "hdd=$HDD" https://status.poitrin.com/logs >/dev/null 2>/dev/null
