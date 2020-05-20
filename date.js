//Shows full date eg: Monday, April 29, 2002 
function show_full_date()
{
var mydate=new Date();
var year=mydate.getYear();

if (year < 1000)
	year+=1900;
	var day=mydate.getDay();
	var month=mydate.getMonth();
	var daym=mydate.getDate();

if (daym<10)
	daym="0"+daym;
	
	var dayarray=new Array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");
	var montharray=new Array("January","February","March","April","May","June","July","August","September","October","November","December");
	document.write(dayarray[day]+", "+montharray[month]+" "+daym+", "+year);
}

//Shows year only eg: 2002
function show_year()
{
var mydate=new Date();
var year=mydate.getYear();
if (year < 1000)
year+=1900;

document.write(" <font size='2' face='Arial, Helvetica, sans-serif'>"+year+"</font>");
}