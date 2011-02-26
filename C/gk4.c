/*
gcc gk4.c -o gk4 -mwindows -Wl,-subsystem,console -lcomctl32 
gk4.c 
the main gui part with comctl
CoInitializeEx 

*/
#define _WIN32_IE 0x0400


#include <windows.h>
#include <stdio.h>
#include <stdlib.h>
#include <string.h>

#include <commctrl.h>
#include <richedit.h>

#ifndef LVS_EX_FULLROWSELECT
#define LVS_EX_FULLROWSELECT  0x00000020
#endif
#ifndef LVS_EX_GRIDLINES
#define LVS_EX_GRIDLINES        0x00000001
#endif
#ifndef LV_VIEW_LIST
#define LV_VIEW_LIST 0x00000003
#endif

#define IDC_LIST 1234
#define ID_RICH_EDIT 1235
#define ID_BUTTON1 1236 /// for close button
#define ID_CHECK1 1237  /// for the check box 


typedef struct
{
   char  szItemNr[512]; // about 256 chinese words
}Item;

LRESULT CALLBACK WndProc(HWND, UINT, WPARAM, LPARAM);
static char szAppName[] = "GoldKey_Window";


HINSTANCE hInst;
HWND hwnd; // the core window
HWND hList; // the left list
HWND hwndEdit;
HWND hwndTab;
HWND button;
HWND hCheck;
static int index; // for the List using
Item ListItem[1];

LV_ITEM lv;
LV_COLUMN lvC;
LV_DISPINFO *lvd;
NMHDR *hdr;

CHARFORMAT cf;
CREATESTRUCT  *cs;

void SetDefaultFont (HWND hwnd)
{
	SendMessage(hwnd, WM_SETFONT, (WPARAM)GetStockObject(DEFAULT_GUI_FONT), (LPARAM)TRUE);
}

void create_btns(HWND parent)
{
	//[ ]  今日不再提示已发布的消息  关闭
	RECT rc = {0};
	GetWindowRect(hwnd,&rc);
	button = CreateWindow("Button","关闭",
		BS_PUSHBUTTON | WS_CHILD | WS_VISIBLE ,rc.right-rc.left-70 ,rc.bottom-rc.top-55,50,25,parent,(HMENU)ID_BUTTON1,hInst,0);	
	if(button == NULL)
	{
		MessageBox(NULL, "Create BUTTON Failed!", szAppName, MB_OK);
		return ;
	}
	SetDefaultFont(button);
	//BS_CHECKBOX 
	hCheck =  CreateWindow("Button"," 今日不再提示已发布过的消息",
		BS_AUTOCHECKBOX  | WS_CHILD | WS_VISIBLE ,rc.right-rc.left-340 ,rc.bottom-rc.top-55,230,25,parent,(HMENU)ID_CHECK1,hInst,0);

	if(hCheck == NULL)
	{
		MessageBox(NULL, "Create CHECKBOX Failed!", szAppName, MB_OK);
		return ;
	}
	SetDefaultFont(hCheck);
	
	return;
}// end create_btns 
void create_tab(HWND parent)
{
	RECT tr = {0};
	GetWindowRect(hwnd,&tr);
	tr.bottom-=60;

    hwndTab = CreateWindowEx(
               0,                      // extended style
               WC_TABCONTROL,          // tab control constant
               "",                     // text/caption
               WS_CHILD | WS_VISIBLE,  // is a child control, and visible
               0,                      // X position - device units from left
               0,                      // Y position - device units from top
               160,                    // Width - in device units
               tr.bottom-tr.top,                    // Height - in device units
               parent,            // parent window
               NULL,                   // no menu
               hInst,                 // instance
               NULL                    // no extra junk
           );

    if (hwndTab == NULL)
    {
        // tab creation failed -
        // are the correct #defines in your header?
        // have you included the common control library?
        MessageBox(NULL, "Tab creation failed", "Tab Example", MB_OK | MB_ICONERROR);
        return;
    }

    SetDefaultFont(hwndTab);
    // start adding items to our tab control
    TCITEM tie = {0};  // tab item structure
    CHAR pszTab1 [] = "用户消息";  // tab1's text
  

    // set up tab item structure for Tab1
    tie.mask = TCIF_TEXT;  // we're only displaying text in the tabs
    tie.pszText = pszTab1;  // the tab's text/caption

    // attempt to insert Tab1
    if (TabCtrl_InsertItem(hwndTab, 0, &tie) == -1)
    {
        // couldn't insert tab item
        DestroyWindow(hwndTab);
        MessageBox(NULL, "Couldn't add Tab1", "Tab Example", MB_OK | MB_ICONERROR);
        return;
    }
	
}
void StartCommonControls(DWORD flags)
{
	//load the common controls dll, specifying the type of control(s) required 
	INITCOMMONCONTROLSEX iccx;
	iccx.dwSize=sizeof(INITCOMMONCONTROLSEX);
	iccx.dwICC=flags;
	InitCommonControlsEx(&iccx);
}

BOOL AdjustListView(HWND hList, LV_ITEM *lv, int iItems)
{
   int i = iItems;
   int iRet;
   ListView_DeleteAllItems(hList);

   lv->mask = LVIF_TEXT;
   lv->iSubItem = 0;
   lv->pszText = LPSTR_TEXTCALLBACK;

   while(i > 0)
   {
      iRet = ListView_InsertItem(hList, lv);
      i--;
   }
   return TRUE;
}
void AddText(HWND hwnd, char *szTextIn, COLORREF crNewColor)
{
	char *Text = (char *)malloc(lstrlen(szTextIn) + 5);
	CHARFORMAT cf;
	int iTotalTextLength = GetWindowTextLength(hwnd);
	int iStartPos = iTotalTextLength;
	int iEndPos;

	strcpy(Text, szTextIn);
	strcat(Text, "\r\n");

	SendMessage(hwnd, EM_SETSEL, (WPARAM)(int)iTotalTextLength, (LPARAM)(int)iTotalTextLength);
	SendMessage(hwnd, EM_REPLACESEL, (WPARAM)(BOOL)FALSE, (LPARAM)(LPCSTR)Text);

	free(Text);

	cf.cbSize      = sizeof(CHARFORMAT);
	cf.dwMask      = CFM_COLOR | CFM_UNDERLINE | CFM_BOLD;
	cf.dwEffects   = (unsigned long)~(CFE_AUTOCOLOR | CFE_UNDERLINE | CFE_BOLD);
	cf.crTextColor = crNewColor;

	iEndPos = GetWindowTextLength(hwnd);

	SendMessage(hwnd, EM_SETSEL, (WPARAM)(int)iStartPos, (LPARAM)(int)iEndPos);
	SendMessage(hwnd, EM_SETCHARFORMAT, (WPARAM)(UINT)SCF_SELECTION, (LPARAM)&cf);
	SendMessage(hwnd, EM_HIDESELECTION, (WPARAM)(BOOL)TRUE, (LPARAM)(BOOL)FALSE);

	SendMessage(hwnd, EM_LINESCROLL, (WPARAM)(int)0, (LPARAM)(int)1);
} 
int create_textbox(HWND parent)
{
	RECT tr = {0};
	
	GetWindowRect(hwnd,&tr);
	tr.bottom-=60;
	/*
	hwndEdit = CreateWindowEx(0, "RICHEDIT", "RichTextBox",
         WS_CHILD | WS_CLIPCHILDREN | ES_WANTRETURN | ES_MULTILINE | WS_VISIBLE | ES_LEFT,
         0, 0, 1, 1, parent, (HMENU)ID_RICH_EDIT, hInst, NULL);
	*/
	hwndEdit = CreateWindowEx(WS_EX_CLIENTEDGE, "RichEdit", NULL,
         WS_CHILD | WS_CLIPCHILDREN |WS_VISIBLE | ES_WANTRETURN | ES_MULTILINE | WS_VISIBLE | ES_LEFT,
         163, 1, tr.right-tr.left-163, tr.bottom-tr.top, parent, NULL, hInst, NULL);

	if( hwndEdit ==NULL)
	{
		MessageBox(NULL, "Create hwndEdit Failed!", szAppName, MB_OK);
		return -1;
	}
	//SendMessage(hwndEdit, EM_SETWORDBREAKPROCEX, (WPARAM)0, (LPARAM)NULL);

	cf.cbSize = sizeof (CHARFORMAT);  
	cf.dwMask = CFM_FACE | CFM_SIZE;
	cf.yHeight = 180;
	strcpy(cf.szFaceName, "MS Sans Serif");
	SendMessage(hwndEdit, EM_SETCHARFORMAT, (WPARAM)(UINT)0, (LPARAM)&cf);

	AddText(hwndEdit, "Hello world", RGB(0,0,0));
	
}// end create_textbox
int create_list(HWND parent)
{		
	RECT tr = {0}; // rect structure to hold tab size
        //TabCtrl_GetItemRect(hwndTab, 0, &tr);
	GetWindowRect(hwndTab,&tr);
	hList = CreateWindowEx(0, WC_LISTVIEW, "", WS_VISIBLE | WS_CHILD | WS_VSCROLL | LVS_REPORT |LVS_LIST,
         0, 18, tr.right-tr.left , tr.bottom - tr.top - 18, parent,(HMENU)IDC_LIST, hInst, NULL);
	//ListView_SetExtendedListViewStyle(hList, LVS_EX_FULLROWSELECT | LVS_EX_GRIDLINES);
	if(hList ==NULL) 
	{
		MessageBox(NULL, "Create hList Failed!", szAppName, MB_OK);
		return -1;
	}
	SendMessage(hList,LVM_SETEXTENDEDLISTVIEWSTYLE,
           0,LVS_EX_FULLROWSELECT); 

	ListView_SetView(hList, LV_VIEW_LIST);
	ShowScrollBar(hList,SB_VERT,TRUE);

	lvC.mask = LVCF_FMT | LVCF_WIDTH | LVCF_TEXT | LVCF_SUBITEM;
	lvC.fmt = LVCFMT_LEFT;

	index= 0;
	lvC.iSubItem = index;
	lvC.cx = 150;
	lvC.pszText = "news";
	ListView_InsertColumn(hList,index,&lvC);
	strcpy(ListItem[0].szItemNr, "2010-02-12 前面一句来自三国志·吴志·周瑜传，后面一句来自苏轼的念奴娇·赤壁怀古。放到这2天的行情中很贴切。周瑜是个音乐奇才，听人演奏的时候，即使多喝了几杯酒...");
	index++;
	AdjustListView(hList, &lv, index);			
}

int create_W(HINSTANCE h)
{
	WNDCLASS wndclass;

	wndclass.style = CS_HREDRAW | CS_VREDRAW;
	wndclass.lpfnWndProc = WndProc;
	wndclass.cbClsExtra = 0;
	wndclass.cbWndExtra = 0;
	wndclass.hInstance = h;
	wndclass.hIcon = LoadIcon(NULL, IDI_APPLICATION);
	wndclass.hCursor = LoadCursor(NULL, IDC_ARROW);
	//wndclass.hbrBackground = (HBRUSH) GetStockObject(WHITE_BRUSH);
	//wndclass.hbrBackground = (HBRUSH)(COLOR_BACKGROUND);
	wndclass.hbrBackground =  GetSysColorBrush(COLOR_BTNFACE);
	wndclass.lpszMenuName = NULL;

	wndclass.lpszClassName = szAppName;

	if (!RegisterClass(&wndclass)) {
    		MessageBox(NULL, TEXT("This program requires Windows NT!"), szAppName, MB_ICONERROR);
    		return 0;
	}
	
	hwnd = CreateWindow(szAppName,
		"金钥匙 - 消息提示板",
		WS_OVERLAPPEDWINDOW,
		CW_USEDEFAULT,
		CW_USEDEFAULT,
		CW_USEDEFAULT,
		CW_USEDEFAULT,
		NULL,
		NULL,
		h,
		NULL);

	ShowWindow(hwnd, 1);
	UpdateWindow(hwnd);
	return 1;
}
int init()
{
	hwnd = NULL;
	hList = NULL;
	hwndEdit = NULL;
	index = 0;
	StartCommonControls(ICC_LISTVIEW_CLASSES);

}

int deinit()
{

}

int main( int argc,char**argv)
{
	MSG msg;
	HINSTANCE   hRichEdit; 
	init(); 

	hRichEdit = LoadLibrary("RICHED32.DLL");
	hInst= GetModuleHandle (0);
	create_W(hInst);
	create_tab(hwnd);
	create_list(hwndTab);
	create_textbox(hwnd);
	create_btns(hwnd);

	while (GetMessage(&msg, NULL, 0, 0)) 
	{
		TranslateMessage(&msg);
    		DispatchMessage(&msg);
	}

	FreeLibrary(hRichEdit);
	return msg.wParam;
}

LRESULT CALLBACK WndProc(HWND hwnd, UINT message, WPARAM wParam, LPARAM lParam)
{
	HDC hdc;
	PAINTSTRUCT ps;
	RECT rect;
	int w,h;
	
	switch (message) 
	{	
		/*
		case WM_PAINT:
		{
			hdc = BeginPaint(hwnd, &ps);
			GetClientRect(hwnd, &rect);
			DrawText(hdc, TEXT("Hello world!"), -1, &rect, DT_SINGLELINE | DT_CENTER | DT_VCENTER);
			EndPaint(hwnd, &ps);
			return 0;
		}break;
		*/
		case WM_COMMAND:
		{
			switch(LOWORD(wParam))
			{
				case ID_CHECK1:
				{
					int state = SendMessage(hCheck,BM_GETCHECK,0,0);
					if(state == BST_CHECKED) //yes it is checked
					{
					}
					else
					{	
					}
				}break;
				case ID_BUTTON1:
				{
					PostQuitMessage(0);	
				}
			}
		}break;
		case WM_DESTROY:
			PostQuitMessage(0);
		break;
		case WM_SIZE:
		{
			RECT rc={0};
			GetWindowRect(hwnd,&rc);
			w = LOWORD(lParam); h = HIWORD(lParam); h-=30;
			SetWindowPos(hwndTab, HWND_TOP, 0,0,160,h, SWP_NOMOVE);
			SetWindowPos(hwndEdit, HWND_TOP,0,0,w - 163,h, SWP_NOMOVE); 
			SetWindowPos(button, HWND_TOP , rc.right-rc.left-70, rc.bottom-rc.top-53,0,0, SWP_NOSIZE);
			SetWindowPos(hCheck, HWND_TOP,  rc.right-rc.left-340,rc.bottom-rc.top-53,0,0, SWP_NOSIZE);
		}break;
		case WM_NOTIFY :
			switch(((LPNMHDR)lParam)->code)
      			{
      				case NM_DBLCLK :
			        	hdr = (NMHDR FAR*)lParam;
         				if(hdr->hwndFrom == hList)
         				{
            					index = ListView_GetNextItem(hList,-1,LVNI_SELECTED);
            					if(index != -1)
            					{
               						MessageBox(hwnd, "index", "Doubleclicked on this item", MB_OK);
            					}
         				}
         			break;
      				case LVN_GETDISPINFO :
         				lvd = (LV_DISPINFO FAR*)lParam;
         				if((((LPNMHDR)lParam)->hwndFrom == hList))
         				{
            					switch(lvd->item.iSubItem)
            					{
            						case 0:
               							lvd->item.pszText =  ListItem[lvd->item.iItem].szItemNr;
               						break;
            						               						
            					}            			
         				}
				break;	
      			}
		break;
		default:break;
	}

	return DefWindowProc(hwnd, message, wParam, lParam);

}
