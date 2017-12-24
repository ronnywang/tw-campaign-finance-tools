# tw-campaign-finance-tools

舊的 repository 位置在 https://github.com/ronnywang/tw-campaign-finance

但是裡面因為已經太過混亂，因此決定重新開一個新的 repository 把程式碼慢慢轉移過來。

步驟
====
1. 把 PDF 切成 n 張圖檔
   * 需要有安裝 xpdf (為了有 pdfimages 把 pdf 切成 n 張圖片) 和 imagemagick (把 pbm 轉成 png 並做裁切)
   * 把檔案放進 data/ 資料夾
   * php pdf-to-png.php data/input.pdf
   * 接下來圖檔會以一頁一檔被塞入 data/input.pdf.png/png-xxx.png 的檔案中
   * 如果有合併儲存格的話，可以加上有幾行幾列的參數
     * 例如 php pdf-to-png.php data/input.pdf [row] [col]
2. 從圖檔中開始初步切豆腐
   * 需安裝 php-gd
   * php get-cell.php [資料夾]
