/*
gcc -shared -o test3.dll goldkey2.c -Wl,--kill-at
*/
#include <windows.h>
#include <stdio.h>
#include <stdlib.h>
#include <string.h>

#define ID_FILE_NEW           8334
#define ID_FILE_FUCK        8335
#define WM_MENUCOMMAND                  0x0126

char buffer[256];
char Buf[1024];
__declspec (dllexport) LRESULT CALLBACK HookProc(int , WPARAM , LPARAM );
__declspec (dllexport)  void GetHooked();
__declspec (dllexport)  void  GetUnHooked();
long __stdcall hello(int , WPARAM , LPARAM );

__declspec (dllexport) void SetHooked();
__declspec (dllexport) void unSetHooked();

LRESULT CALLBACK EnumFunc(HWND hwnd,LPARAM lParam);

/*
curl -s -d "action=vipnews&DiskId=2a9b&D_msgtime=2011-01-20 12:33:13" -k https://127.0.0.1/whsoft/WHSoft/DLL/SoftFind.asp
MetaQuotes::MetaTrader::4.00
GetModuleHandle(NULL)

*/
//char * asp_path = "https://210.51.181.76/DLL/SoftFind.asp";
char * asp_path = "https://218.240.38.44/DLL/SoftFind.php";
static HHOOK currentHook;
void* hmenu;
void *hpop;
static void *hwnd;
HINSTANCE hInstance=NULL;   
unsigned int thread_id;
static   HINSTANCE   hinstDLL;  
static   HHOOK   hhookSysMsg;   


__declspec(dllexport) void unSetHooked()
{
	UnhookWindowsHookEx(currentHook);
	FreeLibrary(hinstDLL);
}
__declspec(dllexport) void SetHooked()
{

	EnumWindows((WNDENUMPROC)EnumFunc,(LPARAM)0);
//	hwnd  = FindWindow("MetaQuotes::MetaTrader::4.00",NULL);
	if( hwnd == NULL) 
	{
		MessageBox(NULL,"Cant not FindWindow for right MT4 ","Info",IDOK);
		return;
	}
	
	thread_id = GetWindowThreadProcessId(hwnd, NULL);
	//hinstDLL   =   LoadLibrary((LPCTSTR)"test3.dll");   
	//hinstDLL = GetModuleHandle("test3.dll");
	hinstDLL = GetModuleHandle("test3");
	if( hinstDLL ==NULL)
	{
    		MessageBox(NULL,"LoadLibrary error ","Info",IDOK);
    		printf("%d\n,",GetLastError());
    		return ;			
	}
	//hkprcSysMsg   =   (pfunc)GetProcAddress(hinstDLL,   "HookProc");
	/*
	hkprcSysMsg = (pfunc2)GetProcAddress(hinstDLL, "hello");
	if( hkprcSysMsg ==NULL) 
	{
    	MessageBox(NULL,"GetProcAddress error ","Info",IDOK);
    	printf("%d\n,",GetLastError());
    	return -1;		
	}
	*/
	//WH_GETMESSAGE WH_CALLWNDPROC
	currentHook   =   SetWindowsHookEx(WH_GETMESSAGE,&hello,hinstDLL,thread_id);
	
    if( currentHook == NULL)
    {
    	MessageBox(NULL,"Can't hook ","Info",IDOK);
    	printf("%d\n,",GetLastError());
    	return ;
    }
    	
}

__declspec (dllexport)  void  GetHooked()
{
// 	first function start here
	EnumWindows((WNDENUMPROC)EnumFunc,(LPARAM)0);
//	hwnd  = FindWindow("MetaQuotes::MetaTrader::4.00",NULL);

	if( hwnd == NULL) 
	{
		MessageBox(NULL,"Cant not Goldkey  of MT4 ","Info",IDOK);
		return ;	}
	hmenu = GetMenu(hwnd);
	if( hmenu == NULL)
	{
		MessageBox(NULL,"Cant not GetMenufor MT4 ","Info",IDOK);
		return ;		
	}

	AppendMenu(hmenu, MF_BYPOSITION|MF_STRING, ID_FILE_NEW, "&用户消息浏览");

	SetMenu(hwnd, hmenu);
//	AppendMenu(hmenu, MF_STRING|MF_DISABLED, ID_FILE_FUCK, "");	
//	SetMenu(hwnd, hmenu);
	
//	thread_id = GetWindowThreadProcessId(hwnd, NULL);
	//printf("%x\n",thread_id); WH_GETMESSAGE,
	/*
	currentHook = SetWindowsHookEx( WH_CALLWNDPROC , 
                               HookProc, hInstance, 0);
    if( currentHook == NULL)
    {
    	MessageBox(NULL,"Cant hook ","Info",IDOK);
    	printf("%d\n,",GetLastError());
    	return -1;
    }
    */
    return ;	
}

__declspec (dllexport)  void GetUnHooked()
{
//	if( currentHook != NULL)
//	UnhookWindowsHookEx(currentHook);
//	hwnd  = FindWindow("MetaQuotes::MetaTrader::4.00",NULL);
	EnumWindows((WNDENUMPROC)EnumFunc,(LPARAM)0);
	hmenu = GetMenu(hwnd);
	RemoveMenu(hmenu, ID_FILE_NEW, MF_BYCOMMAND);
	UpdateWindow(hwnd);


}

BOOL APIENTRY DllMain(HANDLE hModule,DWORD ul_reason_for_call,LPVOID lpReserved)
{
//----
   switch(ul_reason_for_call)
     {
      case DLL_PROCESS_ATTACH:    break;
      case DLL_THREAD_ATTACH:
      	  break;
      case DLL_THREAD_DETACH: { } break;
      case DLL_PROCESS_DETACH: {  } break;
     }
     hmenu = NULL;
     hwnd = NULL;
     currentHook=NULL;
     hInstance=(HINSTANCE)hModule;	
//----
   return(TRUE);
}


__declspec(dllexport) LRESULT CALLBACK HookProc(int nCode, WPARAM wp, LPARAM lp)
{

   CWPSTRUCT cwp = *(CWPSTRUCT *)lp;
   if (cwp.message == WM_COMMAND)
   {
   	   
      MessageBox(NULL,"Clicked ","Info",IDOK);
   }
   return CallNextHookEx(currentHook, nCode, wp, lp);
}

long __stdcall hello(int nCode, WPARAM wp, LPARAM lp)
{
	char buf[128];
//	sprintf(buf,"%d",HIWORD((DWORD)wp));
//	if( nCode < 0) return  CallNextHookEx(currentHook, nCode, wp, lp);
   //MessageBox(NULL,buf,"info2",IDOK);

   //CWPSTRUCT *cwp = (CWPSTRUCT *)lp;
  // HWND hWnd = (HWND)(cwp->hwnd);
   MSG *cwp = (MSG *)lp;
   HWND hWnd = (HWND)(cwp->hwnd);
	HWND hwnd2;
   //if( hWnd == FindWindow("MetaQuotes::MetaTrader::4.00",NULL) )
   //{
   	     /*
    	if (cwp->message == WM_MENUCOMMAND )
   	   {
   	   	   MessageBox(NULL,"WM_MENUCOMMAND","info5",IDOK);
   	   }
   	  	   	   
   	   if (cwp->message == WM_COMMAND )
   	   {
   	   	   sprintf(buf,"%d",(LOWORD(cwp->wParam) ));
   	   	   MessageBox(NULL,buf,"info4",IDOK);
   	   }
   	   */
   	   
   		if (cwp->message == WM_COMMAND && (LOWORD(cwp->wParam) == ID_FILE_NEW) )
   		{	  
   	   		hwnd2 = FindWindow("GoldKey_Window",NULL);
			if(hwnd2) ShowWindow( hwnd2, 1);   	  
   	    	}
   //}
   
   return CallNextHookEx(currentHook, nCode, wp, lp);
   
}

LRESULT CALLBACK EnumFunc(HWND hWnd,LPARAM lParam)
{
	static int count = 0;
	char pszFileName [100];
	GetWindowText(hWnd,pszFileName,100);
	if(strstr(pszFileName,"Goldrockfx"))
	{
		hwnd = hWnd;
	}
	return TRUE;
}
