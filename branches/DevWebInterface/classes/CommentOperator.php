<?php
/*
 * 03.08.2011 Eren Alp Celik
 * 
 * Ahmet'in MySQLOperator i�erisinde sundu�u komutlar� kullanarak
 * kullan�c�lar�n resimler alt�na girdi�i mesajlar� veritaban�na
 * g�nderir veya mevcut mesajlarda g�ncellemeler silmeler yapar. 
 * 
 * �al��malar sonucunda Adnan'�n BaseTable s�n�f� bu durum i�in kullan��l� olmad�
 * 
 */
require_once('..\classes\tables\BaseTable.php');
require_once('..\classes\MySQLOperator.php');
require_once('..\classes\tables\UploadComment.php');

//�imdilik sadece resimler alt�na yorum koymak i�in kullan�lacak
//sonradan ba�ka itemlara da yorum koyabilecek.
class CommentOperator{
    
    private $baseTable;
    private $dbc;
    private $userId;
    private $photoId;
    private $comment;
    private $result;
	
	public function __construct($userId, $dbc)
    {
    	//Kullan�c� yorum yazmaya yeltendi�inde ilk veritaban� ba�lant�s� kurulsun
    	$this->userId=$userId;
    	$this->dbc=$dbc;
    	
		$this->dbc = new MySQLOperator("localhost","root","","php");
    	$this->baseTable = new BaseTable("traceper_upload_comment", $this->dbc);
    }

	//Gerekli mi bilmiyorum ama ben yine de koydum
    function __destruct() {
    }
	
	//bir resme bakt���nda onun hakk�nda yap�lm�� t�m yorumlar� datetime s�ras�nda getir
	public function fetchComments($photoId) 
	{
		$valuesArray = array(UploadComment::photo_id, UploadComment::user_id, UploadComment::comment_time, UploadComment::comment);
		$condArr = array(UploadComment::photo_id => $photo_id);	
		
		$this->assertTrue($this->baseTable->select($valuesArray, $condArr));
		
		//Select ile ilgili resim i�in yaz�lm�� t�m mesajlar �ekilecek v ekrana parse edilecek
		//ilgili yorumun K���, ZAMAN ve ��ER�K bilgileri �ekilir
		//$sqlQuery="Select ".UploadComment::photo_id.",".UploadComment::user_id.",".
		//          UploadComment::comment_time.",".UploadComment::comment.
		//          "FROM traceper_upload_comment Where photo_id=".$photoId;
		//this->result=$this->dbc->query($sqlQuery);
	}

    //bir yorumu de�i�tirir
	public function editComment($photoId, $comment, $newComment, $commentTime) 
	{
		//�nce de�i�tirilecek yorumun id si �ekilir.
		$fieldsArray = array(UploadComment::comment_id);
		$condArr = array(UploadComment::photo_id => $photo_id, UploadComment::user_id=>$this->usedId,UploadComment::comment=>$comment );	
		$this->comment=$this->baseTable->select($fieldsArray, $condArr);
				
		$updateArray=array(UploadComment::comment => $comment,UploadComment::comment_time => $commentTime )
	    $condArr = array(UploadComment::comment_id => $this->comment);
	    $this->assertTrue($this->baseTable->update($updateArray, $condArr));
	    
		//$sqlQuery="Select ".UploadComment::comment_id.",".
		//          "FROM traceper_upload_comment Where photo_id=".$photoId.
		//          " AND user_id=".$userId." AND comment=".$comment;
		//result ilgili yorumun id sini �eker, birazdan de�i�tirilecek
		//this->result=$this->dbc->query($sqlQuery);
		
		//$sqlQuery="UPDATE traceper_upload_comment ".
        //          "SET comment=".$newComment.", comment_time=".$commentTime.
        //         "WHERE ".UploadComment::comment_id."=".$result;
		//$this->dbc->query($sqlQuery);	

	}	
    
	//yeni yorum gir
    public function insertNewComment($photoId, $commentTime, $comment) 
	{
		$elementsArray=array(UploadComment::photo_id,UploadComment::user_id, UploadComment::comment, UploadComment::comment_time);
		$valuesArray=array($photoId, $this->userId, $commentTime, $comment);
		$this->assertTrue($this->baseTable->insert($elementsArray, $valuesArray));		
		
		//$sqlQuery="INSERT INTO table_name (photo_id, user_id, comment, comment_time) ".
		//"VALUES (".$photoId.",".$this->userId.",".$commentTime.",".$comment.")";
		//this->dbc->query($sqlQuery);
	}
	
	//gerekli durumlarda ilgili yorumlar�n silinmesi i�in
	public function deleteComments($photoId)
	{
		//�nce silinecek yorumun id si �ekilir.
		$fieldsArray = array(UploadComment::comment_id);
		$condArr = array(UploadComment::photo_id => $photo_id, UploadComment::user_id=>$this->usedId,UploadComment::comment=>$comment );	
		$this->result=$this->baseTable->select($fieldsArray, $condArr);
		
		$deleteArray=array(UploadComment::comment_id => $this->result);
		$this->result=$this->assertTrue($this->baseTable->delete($deleteArray));
		
		//$sqlQuery="Select ".UploadComment::comment_id.",".
		//          "FROM traceper_upload_comment Where photo_id=".$photoId.
		//          " AND user_id=".$this->userId." AND comment=".$comment;
	
 		//result silinecek yorumun id sini �eker, birazdan silinecek
		//this->result=$this->dbc->query($sqlQuery);
		//$sqlQuery="DELETE FROM traceper_upload_comment WHERE ".
		//			UploadComment::comment_id."=".$result;
		
	}
}
?>