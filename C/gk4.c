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

static int index; // for the List using
Item ListItem[1];

LV_ITEM lv;
LV_COLUMN lvC;
LV_DISPINFO *lvd;
NMHDR *hdr;

CHARFORMAT cf;
CREATESTRUCT  *cs;

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
	/*
	hwndEdit = CreateWindowEx(0, "RICHEDIT", "RichTextBox",
         WS_CHILD | WS_CLIPCHILDREN | ES_WANTRETURN | ES_MULTILINE | WS_VISIBLE | ES_LEFT,
         0, 0, 1, 1, parent, (HMENU)ID_RICH_EDIT, hInst, NULL);
	*/
	hwndEdit = CreateWindowEx(WS_EX_CLIENTEDGE, "RichEdit", NULL,
         WS_CHILD | WS_CLIPCHILDREN |WS_VISIBLE | ES_WANTRETURN | ES_MULTILINE | WS_VISIBLE | ES_LEFT,
         130, 0, 300, 300, parent, NULL, hInst, NULL);

	if( hwndEdit ==NULL)
	{
		MessageBox(NULL, "Create hwndEdit Failed!", szAppName, MB_OK);
		return -1;
	}
	SendMessage(hwndEdit, EM_SETWORDBREAKPROCEX, (WPARAM)0, (LPARAM)NULL);

	cf.cbSize = sizeof (CHARFORMAT);  
	cf.dwMask = CFM_FACE | CFM_SIZE;
	cf.yHeight = 180;
	strcpy(cf.szFaceName, "MS Sans Serif");
	SendMessage(hwndEdit, EM_SETCHARFORMAT, (WPARAM)(UINT)0, (LPARAM)&cf);

	AddText(hwndEdit, "Hello world", RGB(164,0,0));
	
}// end create_textbox
int create_list(HWND parent)
{		
	hList = CreateWindowEx(0, WC_LISTVIEW, "", WS_VISIBLE | WS_CHILD | WS_VSCROLL | LVS_REPORT |LVS_LIST,
         0, 0, 130, 200, parent,(HMENU)IDC_LIST, hInst, NULL);
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
	wndclass.hbrBackground = (HBRUSH) GetStockObject(WHITE_BRUSH);
	wndclass.lpszMenuName = NULL;

	wndclass.lpszClassName = szAppName;

	if (!RegisterClass(&wndclass)) {
    		MessageBox(NULL, TEXT("This program requires Windows NT!"), szAppName, MB_ICONERROR);
    		return 0;
	}
	
	hwnd = CreateWindow(szAppName,
		"Simple Application Window",
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
	create_list(hwnd);
	create_textbox(hwnd);

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
		case WM_DESTROY:
			PostQuitMessage(0);
		break;
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
