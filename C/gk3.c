/*
gcc gk3.c -o gk3 -mwindows -Wl,-subsystem,console
This exe only do HOOK 
*/
#include <windows.h>
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
	
typedef void (*pfunc)();
typedef void (*pfunc2)();
pfunc2  hkprcSysMsg; 
pfunc mt4_func;
static   HINSTANCE   hinstDLL;  
static   HHOOK   hhookSysMsg;   


int main(int argc,char**argv)
{
	
	hinstDLL   =   LoadLibrary((LPCTSTR)"test3.dll");   
	if( hinstDLL ==NULL)
	{
    	MessageBox(NULL,"LoadLibrary error ","Info",IDOK);
    	printf("%d\n,",GetLastError());
    	return -1;			
	}
	hkprcSysMsg = (pfunc2)GetProcAddress(hinstDLL, "SetHooked");
	if( hkprcSysMsg ==NULL) 
	{
    	MessageBox(NULL,"GetProcAddress1 error ","Info",IDOK);
    	printf("%d\n,",GetLastError());
    	return -1;		
	}
	
	mt4_func = (pfunc)GetProcAddress(hinstDLL, "GetHooked");
	if( mt4_func ==NULL) 
	{
    	MessageBox(NULL,"GetProcAddress2 error ","Info",IDOK);
    	printf("%d\n,",GetLastError());
    	return -1;		
	}
	mt4_func();	
	hkprcSysMsg();
	
	getch();
	
	hkprcSysMsg = (pfunc2)GetProcAddress(hinstDLL, "unSetHooked");
	if( hkprcSysMsg ==NULL) 
	{
    	MessageBox(NULL,"GetProcAddress3 error ","Info",IDOK);
    	printf("%d\n,",GetLastError());
    	return -1;		
	} 
	mt4_func = (pfunc)GetProcAddress(hinstDLL, "GetUnHooked");
	if( mt4_func ==NULL) 
	{
    	MessageBox(NULL,"GetProcAddress4 error ","Info",IDOK);
    	printf("%d\n,",GetLastError());
    	return -1;		
	}	
	hkprcSysMsg();
	mt4_func();
	return 0;
}
