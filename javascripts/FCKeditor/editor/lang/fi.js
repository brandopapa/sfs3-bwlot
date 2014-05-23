/*
 * FCKeditor - The text editor for Internet - http://www.fckeditor.net
 * Copyright (C) 2003-2007 Frederico Caldeira Knabben
 *
 * == BEGIN LICENSE ==
 *
 * Licensed under the terms of any of the following licenses at your
 * choice:
 *
 *  - GNU General Public License Version 2 or later (the "GPL")
 *    http://www.gnu.org/licenses/gpl.html
 *
 *  - GNU Lesser General Public License Version 2.1 or later (the "LGPL")
 *    http://www.gnu.org/licenses/lgpl.html
 *
 *  - Mozilla Public License Version 1.1 or later (the "MPL")
 *    http://www.mozilla.org/MPL/MPL-1.1.html
 *
 * == END LICENSE ==
 *
 * Finnish language file.
 $Id: fi.js 5311 2009-01-10 08:11:55Z hami $
 */

var FCKLang =
{
// Language direction : "ltr" (left to right) or "rtl" (right to left).
Dir					: "ltr",

ToolbarCollapse		: "Piilota työkalurivi",
ToolbarExpand		: "Näytä työkalurivi",

// Toolbar Items and Context Menu
Save				: "Tallenna",
NewPage				: "Tyhjennä",
Preview				: "Esikatsele",
Cut					: "Leikkaa",
Copy				: "Kopioi",
Paste				: "Liitä",
PasteText			: "Liitä tekstinä",
PasteWord			: "Liitä Wordista",
Print				: "Tulosta",
SelectAll			: "Valitse kaikki",
RemoveFormat		: "Poista muotoilu",
InsertLinkLbl		: "Linkki",
InsertLink			: "Lisää linkki/muokkaa linkkiä",
RemoveLink			: "Poista linkki",
Anchor				: "Lisää ankkuri/muokkaa ankkuria",
InsertImageLbl		: "Kuva",
InsertImage			: "Lisää kuva/muokkaa kuvaa",
InsertFlashLbl		: "Flash",
InsertFlash			: "Lisää/muokkaa Flashia",
InsertTableLbl		: "Taulu",
InsertTable			: "Lisää taulu/muokkaa taulua",
InsertLineLbl		: "Murtoviiva",
InsertLine			: "Lisää murtoviiva",
InsertSpecialCharLbl: "Erikoismerkki",
InsertSpecialChar	: "Lisää erikoismerkki",
InsertSmileyLbl		: "Hymiö",
InsertSmiley		: "Lisää hymiö",
About				: "FCKeditorista",
Bold				: "Lihavoitu",
Italic				: "Kursivoitu",
Underline			: "Alleviivattu",
StrikeThrough		: "Yliviivattu",
Subscript			: "Alaindeksi",
Superscript			: "Yläindeksi",
LeftJustify			: "Tasaa vasemmat reunat",
CenterJustify		: "Keskitä",
RightJustify		: "Tasaa oikeat reunat",
BlockJustify		: "Tasaa molemmat reunat",
DecreaseIndent		: "Pienennä sisennystä",
IncreaseIndent		: "Suurenna sisennystä",
Undo				: "Kumoa",
Redo				: "Toista",
NumberedListLbl		: "Numerointi",
NumberedList		: "Lisää/poista numerointi",
BulletedListLbl		: "Luottelomerkit",
BulletedList		: "Lisää/poista luottelomerkit",
ShowTableBorders	: "Näytä taulun rajat",
ShowDetails			: "Näytä muotoilu",
Style				: "Tyyli",
FontFormat			: "Muotoilu",
Font				: "Fontti",
FontSize			: "Koko",
TextColor			: "Tekstiväri",
BGColor				: "Taustaväri",
Source				: "Koodi",
Find				: "Etsi",
Replace				: "Korvaa",
SpellCheck			: "Tarkista oikeinkirjoitus",
UniversalKeyboard	: "Universaali näppäimistö",
PageBreakLbl		: "Sivun vaihto",
PageBreak			: "Lisää sivun vaihto",

Form			: "Lomake",
Checkbox		: "Valintaruutu",
RadioButton		: "Radiopainike",
TextField		: "Tekstikenttä",
Textarea		: "Tekstilaatikko",
HiddenField		: "Piilokenttä",
Button			: "Painike",
SelectionField	: "Valintakenttä",
ImageButton		: "Kuvapainike",

FitWindow		: "Suurenna editori koko ikkunaan",

// Context Menu
EditLink			: "Muokkaa linkkiä",
CellCM				: "Solu",
RowCM				: "Rivi",
ColumnCM			: "Sarake",
InsertRow			: "Lisää rivi",
DeleteRows			: "Poista rivit",
InsertColumn		: "Lisää sarake",
DeleteColumns		: "Poista sarakkeet",
InsertCell			: "Lisää solu",
DeleteCells			: "Poista solut",
MergeCells			: "Yhdistä solut",
SplitCell			: "Jaa solu",
TableDelete			: "Poista taulu",
CellProperties		: "Solun ominaisuudet",
TableProperties		: "Taulun ominaisuudet",
ImageProperties		: "Kuvan ominaisuudet",
FlashProperties		: "Flash ominaisuudet",

AnchorProp			: "Ankkurin ominaisuudet",
ButtonProp			: "Painikkeen ominaisuudet",
CheckboxProp		: "Valintaruudun ominaisuudet",
HiddenFieldProp		: "Piilokentän ominaisuudet",
RadioButtonProp		: "Radiopainikkeen ominaisuudet",
ImageButtonProp		: "Kuvapainikkeen ominaisuudet",
TextFieldProp		: "Tekstikentän ominaisuudet",
SelectionFieldProp	: "Valintakentän ominaisuudet",
TextareaProp		: "Tekstilaatikon ominaisuudet",
FormProp			: "Lomakkeen ominaisuudet",

FontFormats			: "Normaali;Muotoiltu;Osoite;Otsikko 1;Otsikko 2;Otsikko 3;Otsikko 4;Otsikko 5;Otsikko 6",		//REVIEW : Check _getfontformat.html

// Alerts and Messages
ProcessingXHTML		: "Prosessoidaan XHTML:ää. Odota hetki...",
Done				: "Valmis",
PasteWordConfirm	: "Teksti, jonka haluat liittää, näyttää olevan kopioitu Wordista. Haluatko puhdistaa sen ennen liittämistä?",
NotCompatiblePaste	: "Tämä komento toimii vain Internet Explorer 5.5:ssa tai uudemmassa. Haluatko liittää ilman puhdistusta?",
UnknownToolbarItem	: "Tuntemanton työkalu \"%1\"",
UnknownCommand		: "Tuntematon komento \"%1\"",
NotImplemented		: "Komentoa ei ole liitetty sovellukseen",
UnknownToolbarSet	: "Työkalukokonaisuus \"%1\" ei ole olemassa",
NoActiveX			: "Selaimesi turvallisuusasetukset voivat rajoittaa joitain editorin ominaisuuksia. Sinun pitää ottaa käyttöön asetuksista \"Suorita ActiveX komponentit ja -plugin-laajennukset\". Saatat kohdata virheitä ja huomata puuttuvia ominaisuuksia.",
BrowseServerBlocked : "Resurssiselainta ei voitu avata. Varmista, että ponnahdusikkunoiden estäjät eivät ole päällä.",
DialogBlocked		: "Apuikkunaa ei voitu avaata. Varmista, että ponnahdusikkunoiden estäjät eivät ole päällä.",

// Dialogs
DlgBtnOK			: "OK",
DlgBtnCancel		: "Peruuta",
DlgBtnClose			: "Sulje",
DlgBtnBrowseServer	: "Selaa palvelinta",
DlgAdvancedTag		: "Lisäominaisuudet",
DlgOpOther			: "Muut",
DlgInfoTab			: "Info",
DlgAlertUrl			: "Lisää URL",

// General Dialogs Labels
DlgGenNotSet		: "<ei asetettu>",
DlgGenId			: "Tunniste",
DlgGenLangDir		: "Kielen suunta",
DlgGenLangDirLtr	: "Vasemmalta oikealle (LTR)",
DlgGenLangDirRtl	: "Oikealta vasemmalle (RTL)",
DlgGenLangCode		: "Kielikoodi",
DlgGenAccessKey		: "Pikanäppäin",
DlgGenName			: "Nimi",
DlgGenTabIndex		: "Tabulaattori indeksi",
DlgGenLongDescr		: "Pitkän kuvauksen URL",
DlgGenClass			: "Tyyliluokat",
DlgGenTitle			: "Avustava otsikko",
DlgGenContType		: "Avustava sisällön tyyppi",
DlgGenLinkCharset	: "Linkitetty kirjaimisto",
DlgGenStyle			: "Tyyli",

// Image Dialog
DlgImgTitle			: "Kuvan ominaisuudet",
DlgImgInfoTab		: "Kuvan tiedot",
DlgImgBtnUpload		: "Lähetä palvelimelle",
DlgImgURL			: "Osoite",
DlgImgUpload		: "Lisää kuva",
DlgImgAlt			: "Vaihtoehtoinen teksti",
DlgImgWidth			: "Leveys",
DlgImgHeight		: "Korkeus",
DlgImgLockRatio		: "Lukitse suhteet",
DlgBtnResetSize		: "Alkuperäinen koko",
DlgImgBorder		: "Raja",
DlgImgHSpace		: "Vaakatila",
DlgImgVSpace		: "Pystytila",
DlgImgAlign			: "Kohdistus",
DlgImgAlignLeft		: "Vasemmalle",
DlgImgAlignAbsBottom: "Aivan alas",
DlgImgAlignAbsMiddle: "Aivan keskelle",
DlgImgAlignBaseline	: "Alas (teksti)",
DlgImgAlignBottom	: "Alas",
DlgImgAlignMiddle	: "Keskelle",
DlgImgAlignRight	: "Oikealle",
DlgImgAlignTextTop	: "Ylös (teksti)",
DlgImgAlignTop		: "Ylös",
DlgImgPreview		: "Esikatselu",
DlgImgAlertUrl		: "Kirjoita kuvan osoite (URL)",
DlgImgLinkTab		: "Linkki",

// Flash Dialog
DlgFlashTitle		: "Flash ominaisuudet",
DlgFlashChkPlay		: "Automaattinen käynnistys",
DlgFlashChkLoop		: "Toisto",
DlgFlashChkMenu		: "Näytä Flash-valikko",
DlgFlashScale		: "Levitä",
DlgFlashScaleAll	: "Näytä kaikki",
DlgFlashScaleNoBorder	: "Ei rajaa",
DlgFlashScaleFit	: "Tarkka koko",

// Link Dialog
DlgLnkWindowTitle	: "Linkki",
DlgLnkInfoTab		: "Linkin tiedot",
DlgLnkTargetTab		: "Kohde",

DlgLnkType			: "Linkkityyppi",
DlgLnkTypeURL		: "Osoite",
DlgLnkTypeAnchor	: "Ankkuri tässä sivussa",
DlgLnkTypeEMail		: "Sähköposti",
DlgLnkProto			: "Protokolla",
DlgLnkProtoOther	: "<muu>",
DlgLnkURL			: "Osoite",
DlgLnkAnchorSel		: "Valitse ankkuri",
DlgLnkAnchorByName	: "Ankkurin nimen mukaan",
DlgLnkAnchorById	: "Ankkurin ID:n mukaan",
DlgLnkNoAnchors		: "<Ei ankkureita tässä dokumentissa>",		//REVIEW : Change < and > with ( and )
DlgLnkEMail			: "Sähköpostiosoite",
DlgLnkEMailSubject	: "Aihe",
DlgLnkEMailBody		: "Viesti",
DlgLnkUpload		: "Lisää tiedosto",
DlgLnkBtnUpload		: "Lähetä palvelimelle",

DlgLnkTarget		: "Kohde",
DlgLnkTargetFrame	: "<kehys>",
DlgLnkTargetPopup	: "<popup ikkuna>",
DlgLnkTargetBlank	: "Uusi ikkuna (_blank)",
DlgLnkTargetParent	: "Emoikkuna (_parent)",
DlgLnkTargetSelf	: "Sama ikkuna (_self)",
DlgLnkTargetTop		: "Päällimmäisin ikkuna (_top)",
DlgLnkTargetFrameName	: "Kohdekehyksen nimi",
DlgLnkPopWinName	: "Popup ikkunan nimi",
DlgLnkPopWinFeat	: "Popup ikkunan ominaisuudet",
DlgLnkPopResize		: "Venytettävä",
DlgLnkPopLocation	: "Osoiterivi",
DlgLnkPopMenu		: "Valikkorivi",
DlgLnkPopScroll		: "Vierityspalkit",
DlgLnkPopStatus		: "Tilarivi",
DlgLnkPopToolbar	: "Vakiopainikkeet",
DlgLnkPopFullScrn	: "Täysi ikkuna (IE)",
DlgLnkPopDependent	: "Riippuva (Netscape)",
DlgLnkPopWidth		: "Leveys",
DlgLnkPopHeight		: "Korkeus",
DlgLnkPopLeft		: "Vasemmalta (px)",
DlgLnkPopTop		: "Ylhäältä (px)",

DlnLnkMsgNoUrl		: "Linkille on kirjoitettava URL",
DlnLnkMsgNoEMail	: "Kirjoita sähköpostiosoite",
DlnLnkMsgNoAnchor	: "Valitse ankkuri",
DlnLnkMsgInvPopName	: "The popup name must begin with an alphabetic character and must not contain spaces",	//MISSING

// Color Dialog
DlgColorTitle		: "Valitse väri",
DlgColorBtnClear	: "Tyhjennä",
DlgColorHighlight	: "Kohdalla",
DlgColorSelected	: "Valittu",

// Smiley Dialog
DlgSmileyTitle		: "Lisää hymiö",

// Special Character Dialog
DlgSpecialCharTitle	: "Valitse erikoismerkki",

// Table Dialog
DlgTableTitle		: "Taulun ominaisuudet",
DlgTableRows		: "Rivit",
DlgTableColumns		: "Sarakkeet",
DlgTableBorder		: "Rajan paksuus",
DlgTableAlign		: "Kohdistus",
DlgTableAlignNotSet	: "<ei asetettu>",
DlgTableAlignLeft	: "Vasemmalle",
DlgTableAlignCenter	: "Keskelle",
DlgTableAlignRight	: "Oikealle",
DlgTableWidth		: "Leveys",
DlgTableWidthPx		: "pikseliä",
DlgTableWidthPc		: "prosenttia",
DlgTableHeight		: "Korkeus",
DlgTableCellSpace	: "Solujen väli",
DlgTableCellPad		: "Solujen sisennys",
DlgTableCaption		: "Otsikko",
DlgTableSummary		: "Yhteenveto",

// Table Cell Dialog
DlgCellTitle		: "Solun ominaisuudet",
DlgCellWidth		: "Leveys",
DlgCellWidthPx		: "pikseliä",
DlgCellWidthPc		: "prosenttia",
DlgCellHeight		: "Korkeus",
DlgCellWordWrap		: "Tekstikierrätys",
DlgCellWordWrapNotSet	: "<Ei asetettu>",
DlgCellWordWrapYes	: "Kyllä",
DlgCellWordWrapNo	: "Ei",
DlgCellHorAlign		: "Vaakakohdistus",
DlgCellHorAlignNotSet	: "<Ei asetettu>",
DlgCellHorAlignLeft	: "Vasemmalle",
DlgCellHorAlignCenter	: "Keskelle",
DlgCellHorAlignRight: "Oikealle",
DlgCellVerAlign		: "Pystykohdistus",
DlgCellVerAlignNotSet	: "<Ei asetettu>",
DlgCellVerAlignTop	: "Ylös",
DlgCellVerAlignMiddle	: "Keskelle",
DlgCellVerAlignBottom	: "Alas",
DlgCellVerAlignBaseline	: "Tekstin alas",
DlgCellRowSpan		: "Rivin jatkuvuus",
DlgCellCollSpan		: "Sarakkeen jatkuvuus",
DlgCellBackColor	: "Taustaväri",
DlgCellBorderColor	: "Rajan väri",
DlgCellBtnSelect	: "Valitse...",

// Find Dialog
DlgFindTitle		: "Etsi",
DlgFindFindBtn		: "Etsi",
DlgFindNotFoundMsg	: "Etsittyä tekstiä ei löytynyt.",

// Replace Dialog
DlgReplaceTitle			: "Korvaa",
DlgReplaceFindLbl		: "Etsi mitä:",
DlgReplaceReplaceLbl	: "Korvaa tällä:",
DlgReplaceCaseChk		: "Sama kirjainkoko",
DlgReplaceReplaceBtn	: "Korvaa",
DlgReplaceReplAllBtn	: "Korvaa kaikki",
DlgReplaceWordChk		: "Koko sana",

// Paste Operations / Dialog
PasteErrorCut	: "Selaimesi turva-asetukset eivät salli editorin toteuttaa leikkaamista. Käytä näppäimistöä leikkaamiseen (Ctrl+X).",
PasteErrorCopy	: "Selaimesi turva-asetukset eivät salli editorin toteuttaa kopioimista. Käytä näppäimistöä kopioimiseen (Ctrl+C).",

PasteAsText		: "Liitä tekstinä",
PasteFromWord	: "Liitä Wordista",

DlgPasteMsg2	: "Liitä painamalla (<STRONG>Ctrl+V</STRONG>) ja painamalla <STRONG>OK</STRONG>.",
DlgPasteSec		: "Because of your browser security settings, the editor is not able to access your clipboard data directly. You are required to paste it again in this window.",	//MISSING
DlgPasteIgnoreFont		: "Jätä huomioimatta fonttimääritykset",
DlgPasteRemoveStyles	: "Poista tyylimääritykset",
DlgPasteCleanBox		: "Tyhjennä",

// Color Picker
ColorAutomatic	: "Automaattinen",
ColorMoreColors	: "Lisää värejä...",

// Document Properties
DocProps		: "Dokumentin ominaisuudet",

// Anchor Dialog
DlgAnchorTitle		: "Ankkurin ominaisuudet",
DlgAnchorName		: "Nimi",
DlgAnchorErrorName	: "Ankkurille on kirjoitettava nimi",

// Speller Pages Dialog
DlgSpellNotInDic		: "Ei sanakirjassa",
DlgSpellChangeTo		: "Vaihda",
DlgSpellBtnIgnore		: "Jätä huomioimatta",
DlgSpellBtnIgnoreAll	: "Jätä kaikki huomioimatta",
DlgSpellBtnReplace		: "Korvaa",
DlgSpellBtnReplaceAll	: "Korvaa kaikki",
DlgSpellBtnUndo			: "Kumoa",
DlgSpellNoSuggestions	: "Ei ehdotuksia",
DlgSpellProgress		: "Tarkistus käynnissä...",
DlgSpellNoMispell		: "Tarkistus valmis: Ei virheitä",
DlgSpellNoChanges		: "Tarkistus valmis: Yhtään sanaa ei muutettu",
DlgSpellOneChange		: "Tarkistus valmis: Yksi sana muutettiin",
DlgSpellManyChanges		: "Tarkistus valmis: %1 sanaa muutettiin",

IeSpellDownload			: "Oikeinkirjoituksen tarkistusta ei ole asennettu. Haluatko ladata sen nyt?",

// Button Dialog
DlgButtonText		: "Teksti (arvo)",
DlgButtonType		: "Tyyppi",
DlgButtonTypeBtn	: "Button",	//MISSING
DlgButtonTypeSbm	: "Submit",	//MISSING
DlgButtonTypeRst	: "Reset",	//MISSING

// Checkbox and Radio Button Dialogs
DlgCheckboxName		: "Nimi",
DlgCheckboxValue	: "Arvo",
DlgCheckboxSelected	: "Valittu",

// Form Dialog
DlgFormName		: "Nimi",
DlgFormAction	: "Toiminto",
DlgFormMethod	: "Tapa",

// Select Field Dialog
DlgSelectName		: "Nimi",
DlgSelectValue		: "Arvo",
DlgSelectSize		: "Koko",
DlgSelectLines		: "Rivit",
DlgSelectChkMulti	: "Salli usea valinta",
DlgSelectOpAvail	: "Ominaisuudet",
DlgSelectOpText		: "Teksti",
DlgSelectOpValue	: "Arvo",
DlgSelectBtnAdd		: "Lisää",
DlgSelectBtnModify	: "Muuta",
DlgSelectBtnUp		: "Ylös",
DlgSelectBtnDown	: "Alas",
DlgSelectBtnSetValue : "Aseta valituksi",
DlgSelectBtnDelete	: "Poista",

// Textarea Dialog
DlgTextareaName	: "Nimi",
DlgTextareaCols	: "Sarakkeita",
DlgTextareaRows	: "Rivejä",

// Text Field Dialog
DlgTextName			: "Nimi",
DlgTextValue		: "Arvo",
DlgTextCharWidth	: "Leveys",
DlgTextMaxChars		: "Maksimi merkkimäärä",
DlgTextType			: "Tyyppi",
DlgTextTypeText		: "Teksti",
DlgTextTypePass		: "Salasana",

// Hidden Field Dialog
DlgHiddenName	: "Nimi",
DlgHiddenValue	: "Arvo",

// Bulleted List Dialog
BulletedListProp	: "Luettelon ominaisuudet",
NumberedListProp	: "Numeroinnin ominaisuudet",
DlgLstStart			: "Start",	//MISSING
DlgLstType			: "Tyyppi",
DlgLstTypeCircle	: "Kehä",
DlgLstTypeDisc		: "Ympyrä",
DlgLstTypeSquare	: "Neliö",
DlgLstTypeNumbers	: "Numerot (1, 2, 3)",
DlgLstTypeLCase		: "Pienet kirjaimet (a, b, c)",
DlgLstTypeUCase		: "Isot kirjaimet (A, B, C)",
DlgLstTypeSRoman	: "Pienet roomalaiset numerot (i, ii, iii)",
DlgLstTypeLRoman	: "Isot roomalaiset numerot (Ii, II, III)",

// Document Properties Dialog
DlgDocGeneralTab	: "Yleiset",
DlgDocBackTab		: "Tausta",
DlgDocColorsTab		: "Värit ja marginaalit",
DlgDocMetaTab		: "Meta-tieto",

DlgDocPageTitle		: "Sivun nimi",
DlgDocLangDir		: "Kielen suunta",
DlgDocLangDirLTR	: "Vasemmalta oikealle (LTR)",
DlgDocLangDirRTL	: "Oikealta vasemmalle (RTL)",
DlgDocLangCode		: "Kielikoodi",
DlgDocCharSet		: "Merkistäkoodaus",
DlgDocCharSetCE		: "Central European",	//MISSING
DlgDocCharSetCT		: "Chinese Traditional (Big5)",	//MISSING
DlgDocCharSetCR		: "Cyrillic",	//MISSING
DlgDocCharSetGR		: "Greek",	//MISSING
DlgDocCharSetJP		: "Japanese",	//MISSING
DlgDocCharSetKR		: "Korean",	//MISSING
DlgDocCharSetTR		: "Turkish",	//MISSING
DlgDocCharSetUN		: "Unicode (UTF-8)",	//MISSING
DlgDocCharSetWE		: "Western European",	//MISSING
DlgDocCharSetOther	: "Muu merkistäkoodaus",

DlgDocDocType		: "Dokumentin tyyppi",
DlgDocDocTypeOther	: "Muu dokumentin tyyppi",
DlgDocIncXHTML		: "Lisää XHTML julistukset",
DlgDocBgColor		: "Taustaväri",
DlgDocBgImage		: "Taustakuva",
DlgDocBgNoScroll	: "Paikallaanpysyvä tausta",
DlgDocCText			: "Teksti",
DlgDocCLink			: "Linkki",
DlgDocCVisited		: "Vierailtu linkki",
DlgDocCActive		: "Aktiivinen linkki",
DlgDocMargins		: "Sivun marginaalit",
DlgDocMaTop			: "Ylä",
DlgDocMaLeft		: "Vasen",
DlgDocMaRight		: "Oikea",
DlgDocMaBottom		: "Ala",
DlgDocMeIndex		: "Hakusanat (pilkulla erotettuna)",
DlgDocMeDescr		: "Kuvaus",
DlgDocMeAuthor		: "Tekijä",
DlgDocMeCopy		: "Tekijänoikeudet",
DlgDocPreview		: "Esikatselu",

// Templates Dialog
Templates			: "Pohjat",
DlgTemplatesTitle	: "Sisältöpohjat",
DlgTemplatesSelMsg	: "Valitse pohja editoriin<br>(aiempi sisältö menetetään):",
DlgTemplatesLoading	: "Ladataan listaa pohjista. Hetkinen...",
DlgTemplatesNoTpl	: "(Ei määriteltyjä pohjia)",
DlgTemplatesReplace	: "Replace actual contents",	//MISSING

// About Dialog
DlgAboutAboutTab	: "Editorista",
DlgAboutBrowserInfoTab	: "Selaimen tiedot",
DlgAboutLicenseTab	: "Lisenssi",
DlgAboutVersion		: "versio",
DlgAboutInfo		: "Lisää tietoa osoitteesta"
};
