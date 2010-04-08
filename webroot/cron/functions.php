<?php
/**
File Name : functions.php
File Description : Contains all the necessary function for the xml parser
@author : Maycreate
**/

/**
Method Name : collectionTypeInsert
Desc : Parses the Collection type XMl and inserts into DB
**/


function collectionTypeInsert($file,$fh,$logFileWrite)
{
	//creating log file
	$date = date("F j, Y, g:i a");
	$fsize=filesize(ROOTPATH.$file);
	$xmlFileName = explode("/",ROOTPATH.$file);
	$xmlFileName  = $xmlFileName[count($xmlFileName)-1];

	$xmlstr=file_get_contents(ROOTPATH.$file);
	$xml=simplexml_load_string($xmlstr);
	$actionType=addslashes($xml->Action['Type'][0]);
	foreach($xml->Action as $node)
	{

		$productType=addslashes($node->Product['Type'][0]);
		$productTypeName=addslashes($node->Product->TypeName);
		$prodId=addslashes($node->Product->ProdID);
		$result = mysql_query("select ProdID from PRODUCT where ProdID='$prodId'");
		$num_rows = mysql_num_rows($result);
		if($num_rows > 0)
		{
			collectionTypeUpdate($file,$fh);
		}else{
			if(!mysql_query("insert into PRODUCT(ProdID)values('$prodId')")){
				$error=1;
			}
			//UPC is a empty node
			foreach($xml->Action->Product->Availability as $t)
			{
				$availabilityType=addslashes($t['Type'][0]);
				$availabilityStatus=addslashes($t['Status'][0]);
				if(!mysql_query("insert into Availability(ProdID,AvailabilityType,AvailabilityStatus)values('$prodId','$availabilityType','$availabilityStatus')")){
					$error=1;
				}
			}
			//PRODUCT_OFFER
			if(count($xml->Action->Product->PRODUCT_OFFER) != 0){
				foreach($xml->Action->Product->PRODUCT_OFFER as $x)
				{
					$corpCode=addslashes($x->CORP_CODE);
					$labelCode=addslashes($x->LABEL_CODE);
					$labelProductOfferCode=addslashes($x->LABEL_PRODUCT_OFFER_CODE);
					$reportingId=addslashes($x->REPORTING_ID);
					//EXCLUSIVE_IND is a empty node
					$exclusiveInd=addslashes($x->EXCLUSIVE_IND);
					$purchase=addslashes($x->PURCHASE);
					if($corpCode != "" || $labelCode != "" || $labelProductOfferCode != "" || $reportingId != "" || $exclusiveInd != "" || $purchase !="")
					{
						if(!mysql_query("insert into PRODUCT_OFFER(LABEL_PRODUCT_OFFER_CODE,CORP_CODE,LABEL_CODE,REPORTING_ID,PURCHASE,ProdID,EXCLUSIVE_IND)values('$labelProductOfferCode','$corpCode','$labelCode','$reportingId','$purchase','$prodId','$exclusiveInd')")){
							die("insert into PRODUCT_OFFER(LABEL_PRODUCT_OFFER_CODE,CORP_CODE,LABEL_CODE,REPORTING_ID,PURCHASE,ProdID,EXCLUSIVE_IND)values('$labelProductOfferCode','$corpCode','$labelCode','$reportingId','$purchase','$prodId','$exclusiveInd')") ;
						}
						$productOfferId=mysql_insert_id();
						}else{
							$productOfferId= "";
						}

						//SALES_TERRITORY
						$priceCategory=addslashes($x->SALES_TERRITORY->PRICE_CATEGORY);
						$territoryCode=addslashes($x->SALES_TERRITORY->TERRITORY_CODE);
						$salesStartsDate=addslashes($x->SALES_TERRITORY->SALES_START_DATE);
						//SALES_END_DATE IS A EMPTY NODE
						$salesEndDate=addslashes($x->SALES_TERRITORY->SALES_END_DATE);
						if($productOfferId != "")
						{
							if(!mysql_query("insert into SALES_TERRITORY(PRICE_CATEGORY,TERRITORY_CODE,SALES_START_DATE,SALES_END_DATE,PRODUCT_OFFER_ID)values('$priceCategory','$territoryCode','$salesStartsDate','$salesEndDate','$productOfferId')")){
								$error=1;
							}
							$salesTerritoryId=mysql_insert_id();
							}else{
								$salesTerritoryId = "";
								$salesTerritoryId = trim($salesTerritoryId);
							}

							//PRICING
							$currencyCode=addslashes($x->SALES_TERRITORY->PRICING->CURRENCY_CODE);
							$wholeSalePrice=addslashes($x->SALES_TERRITORY->PRICING->WHOLE_SALE_PRICE);
							//SUGGESTED_RETAIL_PRICE IS A EMPTY NODE

							$suggestedRetailPrice=addslashes($x->SALES_TERRITORY->PRICING->SUGGESTED_RETAIL_PRICE);
							if($salesTerritoryId != "" || $salesTerritoryId != 0)
							{
								if(!mysql_query("insert into PRICING(SALES_TERRITORY_ID,CURRENCY_CODE,WHOLE_SALE_PRICE,SUGGESTED_RETAIL_PRICE)values('$salesTerritoryId','$currencyCode','$wholeSalePrice','$suggestedRetailPrice')")){
									$error=1;
								}
							}
							//retailer
							$labelRetailerCode=addslashes($x->SALES_TERRITORY->RETAILER->LABEL_RETAILER_CODE);
							//DISCOUNT_CURRENCY,DISCOUNT_TYPE,VALUE emmpty tags
							$discountCurrency=addslashes($x->SALES_TERRITORY->RETAILER->DISCOUNT_CURRENCY);
							$discountType=addslashes($x->SALES_TERRITORY->RETAILER->DISCOUNT_TYPE);
							$value=addslashes($x->SALES_TERRITORY->RETAILER->VALUE);
							if($salesTerritoryId != "" && $salesTerritoryId != 0 && is_numeric($salesTerritoryId))
							{
								echo $salesTerritoryId."\n";
								fwrite($logFileWrite, $salesTerritoryId."\n");
								if(!mysql_query("insert into RETAILER(SALES_TERRITORY_ID,DISCOUNT_TYPE,LABEL_RETAILER_CODE,DISCOUNT_CURRENCY,DISCOUNT_VALUE)values('$salesTerritoryId','$discountType','$labelRetailerCode','$discountCurrency','$value')")){
									$error=1;
								}

								//DISCOUNT_CURRENCY,DISCOUNT_TYPE,VALUE emmpty tags
								/*if(!mysql_query("insert into RETAILER(LABEL_RETAILER_CODE)values('$labelRetailerCode')")){
								$error=1;
								}*/
							}
						}
					}
					else {
						echo "Product_Offer Node is not present ! ";
						fwrite($logFileWrite, "Product_Offer Node is not present ! \n");

					}
					//Graphic
					foreach($xml->Action->Product->Graphic as $x)
					{
						$imgFormat=addslashes($x->ImgFormat);
						$imgWidth=addslashes($x->ImgWidth);
						$imgHeight=addslashes($x->ImgHeight);
						//File
						$sourceUrl=addslashes($x->File->SourceURL);
						$hostUrl=addslashes($x->File->HostURL);
						if(trim($hostUrl) == "")
						{
							$hostUrl = HOST_URL;
						}
						$saveAsName=addslashes($x->File->SaveAsName);
						$digitalSignature=addslashes($x->File->DigitalSignature);
						$cdnPath = pathToCdn($prodId);

						if(!mysql_query("insert into File(SourceURL,HostURL,SaveAsName,DigitalSignature,CdnPath)values('$sourceUrl','$hostUrl','$saveAsName','$digitalSignature','$cdnPath')")){
							$error=1;
						}
						$fileId= mysql_insert_id();
						$fileCompletePath = str_replace($xmlFileName,$sourceUrl,$file);
						if(trim($sourceUrl) != "")
						{
							$fsize = filesize(ROOTPATH.$fileCompletePath);
							if(trim($saveAsName) != "")
							{
								$sourceUrl = $saveAsName;
							}
							sendFile(ROOTPATH.$fileCompletePath,$sourceUrl,$prodId,$logFileWrite);
							//sendFile(ROOTPATH.$fileCompletePath,CDNPATH.$sourceUrl);
							fwrite($fh,$fileCompletePath."\t".$date."\t".$fsize."\n");
						}
						if(!mysql_query("insert into Graphic(ImgFormat,ImgWidth,ImgHeight,ProdID,FileID)values('$imgFormat','$imgWidth','$imgHeight','$prodId','$fileId')")){
							$error=1;
						}
					}
					//Metadata
					$title=addslashes($node->Product->MetaData->Title);
					$artist=addslashes($node->Product->MetaData->Artist);
					$copyright=addslashes($node->Product->MetaData->Copyright);
					$year=addslashes($node->Product->MetaData->Year);
					//MetaData->Genre=
					$genreName=addslashes($node->Product->MetaData->Genre['name'][0]);
					$subGenreName=addslashes($node->Product->MetaData->Genre->Subgenre['name'][0]);
					if(!mysql_query("insert into Genre(ProdID,Genre,Subgenre)values('$prodId','$genreName','$subGenreName')")){
						$error=1;
					}
					//advisory is a empty node
					$advisory=addslashes($node->Product->MetaData->Advisory);
					$provider=addslashes($node->Product->MetaData->Provider);
					$label=addslashes($node->Product->MetaData->Label);
					foreach($xml->Action->Product->MetaData->Participant as $x)
					{
						$participantName=addslashes($x['name'][0]);
						$participantRole=addslashes($x['role'][0]);
						if(!mysql_query("insert into Participant(ProdID,Role,Name)values('$prodId','$participantRole','$participantName')")){
							$error=1;
						}
					}
					$producer=addslashes($node->Product->MetaData->Producer);
					$artistUrl=addslashes($node->Product->MetaData->ArtistURL);
					$artistLinkText=addslashes($node->Product->MetaData->ArtistLinkText);
					$artistInfoDetail=addslashes($node->Product->MetaData->ArtistInfoDetail);
					$productUrl=addslashes($node->Product->MetaData->ProductUrl);
					$productLinkText=addslashes($node->Product->MetaData->ProductLinkText);
					$productInfoDetail=addslashes($node->Product->MetaData->ProductInfoDetail);
					//Physical Product..
					$physicalProductTitle=addslashes($node->Product->MetaData->PhysicalProduct->Title);
					$referenceId=addslashes($node->Product->MetaData->PhysicalProduct->ReferenceID);
					$productCode=addslashes($node->Product->MetaData->PhysicalProduct->ProductCode);
					$artistText=addslashes($node->Product->MetaData->PhysicalProduct->ArtistText);
					$mediaCount=addslashes($node->Product->MetaData->PhysicalProduct->MediaCount);
					$trackBundleCount=addslashes($node->Product->MetaData->PhysicalProduct->TrackBundleCount);
					$trackCount=addslashes($node->Product->MetaData->PhysicalProduct->TrackCount);
					$releaseDate=addslashes($node->Product->MetaData->PhysicalProduct->ReleaseDate);
					$physicalProductId=addslashes($node->Product->MetaData->PhysicalProduct->ProductID);
					$mediaNo=addslashes($node->Product->MetaData->PhysicalProduct->MediaNo);
					$trackNo=addslashes($node->Product->MetaData->PhysicalProduct->TrackNo);
					$version=addslashes($node->Product->MetaData->PhysicalProduct->Version);
					foreach($xml->Action->Product->Track as $x)
					{
						$trkId=addslashes($x->TrkID);
						$sequenceNum=addslashes($x->SequenceNum);
						if(!mysql_query("insert into TRACK(TrkID,ProdID,SequenceNum)values('$trkId','$prodId','$sequenceNum')")){
							$error=1;
						}
					}
					if(!mysql_query("insert into PhysicalProduct(ProdID,ReferenceID,Title,ArtistText,MediaCount,TrackBundleCount,TrackCount,ReleaseDate,ProductID,MediaNo,TrackNo,Version,CreatedOn)values('$prodId','$referenceId','$physicalProductTitle','$artistText','$mediaCount','$trackBundleCount','$trackCount','$releaseDate','$physicalProductId','$mediaNo','$trackNo','$version',now())")){
						$error=1;
					}

					if(!mysql_query("insert into METADATA(ProdID,Title,Version,Artist,Copyright,Year,Advisory,Provider,Label,Producer,ArtistURL,ArtistLinkText,ArtistInfoDetail,ProductURL,ProductInfoDetail,ProductLinkText)values('$prodId','$title','$version','$artist','$copyright','$year','$advisory','$provider','$label','$producer','$artistUrl','$artistLinkText','$artistInfoDetail','$productUrl','$productInfoDetail','$productLinkText')")){
						$error=1;
					}
					fwrite($fh,$file."\t".$date."\t".$fsize."\n");
				}
			}
		}

/**
Method Name : trackTypeInsert
Desc : Parses the Track type XMl and inserts into DB
**/

function trackTypeInsert($file,$fh,$logFileWrite)
{
	//creating log file
	$date = date("F j, Y, g:i a");
	$fsize=filesize(ROOTPATH.$file);
	$xmlFileName = explode("/",ROOTPATH.$file);
	$xmlFileName  = $xmlFileName[count($xmlFileName)-1];
	//fwrite($fh,$file."\t".$date."\t".$fsize."\n");
	$xmlstr=file_get_contents(ROOTPATH.$file);
	$xml=simplexml_load_string($xmlstr);
	//$action_type=$xml->Action['Type'][0];
	foreach($xml->Action as $node)
	{
		$productType=addslashes($node->Product['Type'][0]);
		$typeName=addslashes($node->Product->TypeName);
		$prodId=addslashes($node->Product->ProdID);
		$result = mysql_query("select ProdID from PRODUCT where ProdID='$prodId'");
		$num_rows = mysql_num_rows($result);
		if($num_rows > 0)
		{
			trackTypeUpdate($file,$fh,$logFileWrite);
		}else{
		
			if(!mysql_query("insert into PRODUCT(ProdID)values('$prodId')")){
				$error=1;
			}
			//UPC is a empty node
			//echo "UPC"."<br>";
			foreach($xml->Action->Product->Availability as $x)
			{
				$availabilityType=addslashes($x['Type'][0]);
				$availabilityStatus=addslashes($x['Status'][0]);
				if(!mysql_query("insert into Availability(ProdID,AvailabilityType,AvailabilityStatus)values('$prodId','$availabilityType','$availabilityStatus')")){
					$error=1;
				}
			}
			//PRODUCT_OFFER
			if(count($xml->Action->Product->PRODUCT_OFFER) != 0)
			{
				foreach($xml->Action->Product->PRODUCT_OFFER as $x)
				{
					$corpCode=addslashes($x->CORP_CODE);
					$labelCode=addslashes($x->LABEL_CODE);
					$labelProductOfferCode=addslashes($x->LABEL_PRODUCT_OFFER_CODE);
					$reportingId=addslashes($x->REPORTING_ID);
					//EXCLUSIVE_IND is a empty node
					$exclusiveInd=addslashes($x->EXCLUSIVE_IND);
					$purchase=addslashes($x->PURCHASE);
					if($corpCode != "" || $labelCode != "" || $labelProductOfferCode != "" || $reportingId != "" || $exclusiveInd != "" || $purchase !="")
					{
						if(!mysql_query("insert into PRODUCT_OFFER(LABEL_PRODUCT_OFFER_CODE,CORP_CODE,LABEL_CODE,REPORTING_ID,PURCHASE,ProdID,EXCLUSIVE_IND)values('$labelProductOfferCode','$corpCode','$labelCode','$reportingId','$purchase','$prodId','$exclusiveInd')")){
							$error=1;
						}
						$productOfferId=mysql_insert_id();
						}else{
							$productOfferId = "";
						}

						//SALES_TERRITORY
						$priceCategory=addslashes($x->SALES_TERRITORY->PRICE_CATEGORY);
						$territoryCode=addslashes($x->SALES_TERRITORY->TERRITORY_CODE);
						$salesStartsDate=addslashes($x->SALES_TERRITORY->SALES_START_DATE);
						//SALES_END_DATE IS A EMPTY NODE
						$salesEndDate=addslashes($x->SALES_TERRITORY->SALES_END_DATE);
						if($productOfferId != "")
						{
							if(!mysql_query("insert into SALES_TERRITORY(PRICE_CATEGORY,TERRITORY_CODE,SALES_START_DATE,SALES_END_DATE,PRODUCT_OFFER_ID)values('$priceCategory','$territoryCode','$salesStartsDate','$salesEndDate','$productOfferId')")){
								$error=1;
							}
							$salesTerritoryId=mysql_insert_id();
							}else{
								$salesTerritoryId = "";
								$salesTerritoryId = trim($salesTerritoryId);
							}
							//PRICING
							$currencyCode=addslashes($x->SALES_TERRITORY->PRICING->CURRENCY_CODE);
							$wholeSalePrice=addslashes($x->SALES_TERRITORY->PRICING->WHOLE_SALE_PRICE);
							//SUGGESTED_RETAIL_PRICE IS A EMPTY NODE
							$suggestedRetailPrice=addslashes($node->Product->PRODUCT_OFFER->SALES_TERRITORY->PRICING->SUGGESTED_RETAIL_PRICE);
							if($salesTerritoryId != "" || $salesTerritoryId != 0)
							{
								if(!mysql_query("insert into PRICING(SALES_TERRITORY_ID,CURRENCY_CODE,WHOLE_SALE_PRICE,SUGGESTED_RETAIL_PRICE)values('$salesTerritoryId','$currencyCode','$wholeSalePrice','$suggestedRetailPrice')")){
									$error=1;
								}
							}
							//retailer
							$labelRetailerCode=addslashes($x->SALES_TERRITORY->RETAILER->LABEL_RETAILER_CODE);
							//DISCOUNT_CURRENCY,DISCOUNT_TYPE,VALUE emmpty tags
							$discountCurrency=addslashes($x->SALES_TERRITORY->RETAILER->DISCOUNT_CURRENCY);
							$discountType=addslashes($x->SALES_TERRITORY->RETAILER->DISCOUNT_TYPE);
							$value=addslashes($x->SALES_TERRITORY->RETAILER->VALUE);
							if($salesTerritoryId != "" && $salesTerritoryId != 0 && is_numeric($salesTerritoryId))
							{
								echo $salesTerritoryId."\n";
								fwrite($logFileWrite, $salesTerritoryId."\n");
								if(!mysql_query("insert into RETAILER(SALES_TERRITORY_ID,DISCOUNT_TYPE,LABEL_RETAILER_CODE,DISCOUNT_CURRENCY,DISCOUNT_VALUE)values('$salesTerritoryId','$discountType','$labelRetailerCode','$discountCurrency','$value')")){
									$error=1;
								}
							}
						}
					}
					else
					{
						echo "Track Type Product_Offer Node is not present ! ";
						fwrite($logFileWrite, "Track Type Product_Offer Node is not present ! ");
					}
					$trkId=addslashes($node->Product->Track->TrkID);
					$seqNum=addslashes($node->Product->Track->SequenceNum);
					if(!mysql_query("insert into TRACK(TrkID,ProdID,SequenceNum)values('$trkId','$prodId','$seqNum')")){
						$error=1;
					}
					//AudioSampleClip
					foreach($xml->Action->Product->Track->AudioSampleClip as $x)
					{
						$audioName=addslashes($x->AudioName);
						$audioType=addslashes($x->AudioType);
						$isClip=addslashes($x->IsClip);
						$audioCodec=addslashes($x->Codec);
						$audioBitRate=addslashes($x->BitRate);
						$audioNumChannels=addslashes($x->NumChannels);
						$audioSampleRate=addslashes($x->SampleRate);
						$audioBitPerSample=addslashes($x->BitPerSample);
						$audioDuration=addslashes($x->Duration);
						$clipOffsetStart=addslashes($x->ClipOffsetStart);
						$clipOffsetEnd=addslashes($x->ClipOffsetEnd);
						$sourceUrl=addslashes($x->File->SourceURL);
						$hostUrl=addslashes($x->File->HostURL);
						if(trim($hostUrl) == "")
						{
							$hostUrl = HOST_URL;
						}
						$saveAsName=addslashes($x->File->SaveAsName);
						$digitalSignature=addslashes($x->File->DigitalSignature);
						$cdnPath = pathToCdn($prodId);
						if(!mysql_query("insert into File(SourceURL,HostURL,SaveAsName,DigitalSignature,CdnPath)values('$sourceUrl','$hostUrl','$saveAsName','$digitalSignature','$cdnPath')")){
							$error=1;
						}
						$fileId= mysql_insert_id();
						$fileCompletePath = str_replace($xmlFileName,$sourceUrl,$file);
						if(trim($sourceUrl)!= "")
						{
							$fsize = filesize(ROOTPATH.$fileCompletePath);
							if(trim($saveAsName) != "")
							{
								$sourceUrl = $saveAsName;
							}
							sendFile(ROOTPATH.$fileCompletePath,$sourceUrl,$prodId,$logFileWrite);
							//sendFile(ROOTPATH.$fileCompletePath,CDNPATH.$sourceUrl);
							fwrite($fh,$fileCompletePath."\t".$date."\t".$fsize."\n");
						}
						if(!mysql_query("insert into Audio(AudioName,AudioType,IsClip,CODEC,Bitrate,NumChannels,SampleRate,BitPerSample,Duration,ClipOffsetStart,ClipOffsetEnd,TrkID,FileID)values('$audioName','$audioType','$isClip','$audioCodec','$audioBitRate','$audioNumChannels','$audioSampleRate','$audioBitPerSample','$audioDuration','$clipOffsetStart','$clipOffsetEnd','$trkId','$fileId')")){
							$error=1;
						}

					}
					//AudioDownload
					foreach($xml->Action->Product->Track->AudioDownload as $x)
					{
						$audioName=addslashes($x->AudioName);
						$audioType=addslashes($x->AudioType);
						$isClip=addslashes($x->IsClip);
						$audioCodec=addslashes($x->Codec);
						$audioBitRate=addslashes($x->BitRate);
						$audioNumChannels=addslashes($x->NumChannels);
						$audioSampleRate=addslashes($x->SampleRate);
						$audioBitPerSample=addslashes($x->BitPerSample);
						$audioDuration=addslashes($x->Duration);
						$clipOffsetStart=addslashes($x->ClipOffsetStart);
						$clipOffsetEnd=addslashes($x->ClipOffsetEnd);
						$sourceUrl=addslashes($x->File->SourceURL);
						$hostUrl=addslashes($x->File->HostURL);
						if(trim($hostUrl) == "")
						{
							$hostUrl = HOST_URL;
						}
						$saveAsName=addslashes($x->File->SaveAsName);
						$digitalSignature=addslashes($x->File->DigitalSignature);
						$cdnPath = pathToCdn($prodId);
						if(!mysql_query("insert into File(SourceURL,HostURL,SaveAsName,DigitalSignature,CdnPath)values('$sourceUrl','$hostUrl','$saveAsName','$digitalSignature','$cdnPath')")){
							$error=1;
						}
						$fileId= mysql_insert_id();
						
						$fileCompletePath = str_replace($xmlFileName,$sourceUrl,$file);
						if(trim($sourceUrl) != "")
						{
							$fsize = filesize(ROOTPATH.$fileCompletePath);
							if(trim($saveAsName) != "")
							{
								$sourceUrl = $saveAsName;
							}
							sendFile(ROOTPATH.$fileCompletePath,$sourceUrl,$prodId,$logFileWrite);
							//sendFile(ROOTPATH.$fileCompletePath,CDNPATH.$sourceUrl);
							fwrite($fh,$fileCompletePath."\t".$date."\t".$fsize."\n");
						}
						if(!mysql_query("insert into Audio(AudioName,AudioType,IsClip,CODEC,Bitrate,NumChannels,SampleRate,BitPerSample,Duration,ClipOffsetStart,ClipOffsetEnd,TrkID,FileID)values('$audioName','$audioType','$isClip','$audioCodec','$audioBitRate','$audioNumChannels','$audioSampleRate','$audioBitPerSample','$audioDuration','$clipOffsetStart','$clipOffsetEnd','$trkId','$fileId')")){
							$error=1;
						}

					}
					//Graphic
					foreach($xml->Action->Product->Track->Graphic as $x)
					{
						$imgFormat=addslashes($x->ImgFormat);
						$imgWidth=addslashes($x->ImgWidth);
						$imgHeight=addslashes($x->ImgHeight);
						//File
						$sourceUrl=addslashes($x->File->SourceURL);
						$hostUrl=addslashes($x->File->HostURL);
						if(trim($hostUrl) == "")
						{
							$hostUrl = HOST_URL;
						}
						$saveAsName=addslashes($x->File->SaveAsName);
						$digitalSignature=addslashes($x->File->DigitalSignature);
						$cdnPath = pathToCdn($prodId);
						if(!mysql_query("insert into File(SourceURL,HostURL,SaveAsName,DigitalSignature,CdnPath)values('$sourceUrl','$hostUrl','$saveAsName','$digitalSignature','$cdnPath')")){
							$error=1;
						}
						$fileId= mysql_insert_id();
						$fileCompletePath = str_replace($xmlFileName,$sourceUrl,$file);
						if(trim($sourceUrl) != "")
						{
							$fsize = filesize(ROOTPATH.$fileCompletePath);
							if(trim($saveAsName) != "")
							{
								$sourceUrl = $saveAsName;
							}
							sendFile(ROOTPATH.$fileCompletePath,$sourceUrl,$prodId,$logFileWrite);
							//sendFile(ROOTPATH.$fileCompletePath,CDNPATH.$sourceUrl);
							fwrite($fh,$fileCompletePath."\t".$date."\t".$fsize."\n");
						}
						if(!mysql_query("insert into Graphic(ImgFormat,ImgWidth,ImgHeight,ProdID,FileID)values('$imgFormat','$imgWidth','$imgHeight','$prodId','$fileId')")){
							$error=1;
						}
					}
					//Metadata
					$title=addslashes($node->Product->Track->MetaData->Title);
					$version=addslashes($node->Product->Track->MetaData->Version);
					$artist=addslashes($node->Product->Track->MetaData->Artist);
					$isrc=addslashes($node->Product->Track->MetaData->ISRC);
					$copyright=addslashes($node->Product->Track->MetaData->Copyright);
					$year=addslashes($node->Product->Track->MetaData->Year);
					//MetaData->Genre
					$genreName=addslashes($node->Product->Track->MetaData->Genre['name'][0]);
					$subGenreName=addslashes($node->Product->Track->MetaData->Genre->Subgenre['name'][0]);
					if(!mysql_query("insert into Genre(ProdID,Genre,Subgenre)values('$prodId','$genreName','$subGenreName')")){
						$error=1;
					}
					$advisory=addslashes($node->Product->Track->MetaData->Advisory);
					$provider=addslashes($node->Product->Track->MetaData->Provider);
					$genreLabel=addslashes($node->Product->Track->MetaData->Label);
					foreach($xml->Action->Product->Track->MetaData->Participant as $x)
					{
						$participantName=addslashes($x['name'][0]);
						$participantRole=addslashes($x['role'][0]);
						if(!mysql_query("insert into Participant(ProdID,Role,Name)values('$prodId','$participantRole','$participantName')")){
							$error=1;
						}
					}
					$producer=addslashes($node->Product->Track->MetaData->Producer);
					$publisher=addslashes($node->Product->Track->MetaData->Publisher);
					$songWriter=addslashes($node->Product->Track->MetaData->SongWriter);
					$artistUrl=addslashes($node->Product->Track->MetaData->ArtistURL);
					$artistLinkText=addslashes($node->Product->Track->MetaData->ArtistLinkText);
					$artistInfoDetail=addslashes($node->Product->Track->MetaData->ArtistInfoDetail);
					$productUrl=addslashes($node->Product->Track->MetaData->ProductUrl);
					$productLinkText=addslashes($node->Product->Track->MetaData->ProductLinkText);
					$productInfoDetail=addslashes($node->Product->Track->MetaData->ProductInfoDetail);
					//Physical Product
					$physicalProductTitle=addslashes($node->Product->Track->MetaData->PhysicalProduct->Title);
					$referenceId=addslashes($node->Product->Track->MetaData->PhysicalProduct->ReferenceID);
					$productCode=addslashes($node->Product->Track->MetaData->PhysicalProduct->ProductCode);
					$artistText=addslashes($node->Product->Track->MetaData->PhysicalProduct->ArtistText);
					$mediaCount=addslashes($node->Product->Track->MetaData->PhysicalProduct->MediaCount);
					$trackBundleCount=addslashes($node->Product->Track->MetaData->PhysicalProduct->TrackBundleCount);
					$trackCount=addslashes($node->Product->Track->MetaData->PhysicalProduct->TrackCount);
					$releaseDate=addslashes($node->Product->Track->MetaData->PhysicalProduct->ReleaseDate);
					$physicalProductId=addslashes($node->Product->Track->MetaData->PhysicalProduct->ProductID);
					$mediaNo=addslashes($node->Product->Track->MetaData->PhysicalProduct->MediaNo);
					$trackNo=addslashes($node->Product->Track->MetaData->PhysicalProduct->TrackNo);
					$hiddenTrackIndicator=addslashes($node->Product->Track->MetaData->PhysicalProduct->HiddenTrackIndicator);
					$songSequenceNo=addslashes($node->Product->Track->MetaData->PhysicalProduct->SongSequenceNo);
					$physicalProductVersion=addslashes($node->Product->Track->MetaData->PhysicalProduct->Version);

					//TrackBundle
					$trackBundleTitle=addslashes($node->Product->Track->MetaData->TrackBundle->Title);
					$trackBundleReferenceId=addslashes($node->Product->Track->MetaData->TrackBundle->ReferenceID);
					$trackBundleSequenceNo=addslashes($node->Product->Track->MetaData->TrackBundle->SequenceNo);
					$trackBundleTrackCount=addslashes($node->Product->Track->MetaData->TrackBundle->TrackCount);
					if($trackBundleTitle != "" || $trackBundleReferenceId != "" || $trackBundleSequenceNo != "" || $trackBundleTrackCount != ""  ){
						if(!mysql_query("insert into TrackBundle(ProdID,Title,ReferenceID,SequenceNo,TrackCount)values('$prodId','$trackBundleTitle','$trackBundleReferenceId','$trackBundleSequenceNo','$trackBundleTrackCount')")){
							$error=1;
						}
					}
					if(!mysql_query("insert into PhysicalProduct(ProdID,Title,ReferenceID,ProductCode,ArtistText,MediaCount,TrackBundleCount,TrackCount,ReleaseDate,ProductID,MediaNo,TrackNo,HiddenTrackIndicator,SongSequenceNo,Version,CreatedOn)values('$prodId','$physicalProductTitle','$referenceId','$productCode','$artistText','$mediaCount','$trackBundleCount','$trackCount','$releaseDate','$physicalProductId','$mediaNo','$trackNo','$hiddenTrackIndicator','$songSequenceNo','$physicalProductVersion',now())")){

						$error=1;
					}
					//echo "insert into METADATA(ProdID,Title,Version,Artist,ISRC,Copyright,Year,Advisory,Provider,Label,Producer,Publisher,SongWriter,ArtistURL,ArtistLinkText,ArtistInfoDetail,ProductURL,ProductInfoDetail,ProductLinkText)values('$prodId','$title','$version','$artist','$isrc','$copyright','$year','$advisory','$provider','$genreLabel','$producer','$publisher','$songWriter','$artistUrl','$artistLinkText','$artistInfoDetail','$productUrl','$productInfoDetail','$productLinkText')";
					if(!mysql_query("insert into METADATA(ProdID,Title,Version,Artist,ISRC,Copyright,Year,Advisory,Provider,Label,Producer,Publisher,SongWriter,ArtistURL,ArtistLinkText,ArtistInfoDetail,ProductURL,ProductInfoDetail,ProductLinkText)values('$prodId','$title','$version','$artist','$isrc','$copyright','$year','$advisory','$provider','$genreLabel','$producer','$publisher','$songWriter','$artistUrl','$artistLinkText','$artistInfoDetail','$productUrl','$productInfoDetail','$productLinkText')")){
						$error=1;
					}
					fwrite($fh,$file."\t".$date."\t".$fsize."\n");
				}
			}
		}


/**
Method Name : collectionTypeUpdate
Desc : Parses the Collection type XMl and Updates the DB
**/

function collectionTypeUpdate($file,$fh,$logFileWrite)
{
	//creating log file
	$date = date("F j, Y, g:i a");
	$fsize=filesize(ROOTPATH.$file);
	$xmlFileName = explode("/",ROOTPATH.$file);
	$xmlFileName  = $xmlFileName[count($xmlFileName)-1];

	$xmlstr=file_get_contents(ROOTPATH.$file);
	$xml=simplexml_load_string($xmlstr);
	$actionType=addslashes($xml->Action['Type'][0]);
	foreach($xml->Action as $node)
	{
		$productType=addslashes($node->Product['Type'][0]);
		$productTypeName=addslashes($node->Product->TypeName);
		$prodId=addslashes($node->Product->ProdID);
		$result = mysql_query("select ProdID from PRODUCT where ProdID='$prodId'");
		$num_rows = mysql_num_rows($result);
		if($num_rows == 0)
		{
			collectionTypeInsert($file,$fh,$logFileWrite);
		}else{
		
			if(!mysql_query("delete from Availability where ProdID='$prodId'")){
				$error=1;
			}
			foreach($xml->Action->Product->Availability as $t)
			{
				$availabilityType=addslashes($t['Type'][0]);
				$availabilityStatus=addslashes($t['Status'][0]);
				if(!mysql_query("insert into Availability(ProdID,AvailabilityType,AvailabilityStatus)values('$prodId','$availabilityType','$availabilityStatus')")){
					$error=1;
				}
			}
			//PRODUCT_OFFER
			/*deleting data from prduct offer*/
			$getProductOfferID = mysql_query("select  PRODUCT_OFFER_ID from PRODUCT_OFFER where ProdID='$prodId'");
			while($row = mysql_fetch_array($getProductOfferID))
			{
				$productOfferId=$row['PRODUCT_OFFER_ID'];
				$getSalesTerritoryID = mysql_query("select SALES_TERRITORY_ID from SALES_TERRITORY where PRODUCT_OFFER_ID='$productOfferId'");
				while($row = mysql_fetch_array($getSalesTerritoryID))
				{
					$salesTerritoryId = $row['SALES_TERRITORY_ID'];
					if(!mysql_query("delete from RETAILER where SALES_TERRITORY_ID='$salesTerritoryId'"))
					{
						$error=1;
					}
					if(!mysql_query("delete from PRICING where SALES_TERRITORY_ID='$salesTerritoryId'"))
					{
						$error=1;
					}
				}
				if(!mysql_query("delete from SALES_TERRITORY where PRODUCT_OFFER_ID='$productOfferId'"))
				{
					$error=1;
				}
			}
			if(!mysql_query("delete from PRODUCT_OFFER where ProdID='$prodId'"))
			{
				$error=1;
			}
			/*end of deleting data*/

			if(count($xml->Action->Product->PRODUCT_OFFER) != 0)
			{
				foreach($xml->Action->Product->PRODUCT_OFFER as $x)
				{
					$corpCode=addslashes($x->CORP_CODE);
					$labelCode=addslashes($x->LABEL_CODE);
					$labelProductOfferCode=addslashes($x->LABEL_PRODUCT_OFFER_CODE);
					$reportingId=addslashes($x->REPORTING_ID);
					$exclusiveInd=addslashes($x->EXCLUSIVE_IND);
					$purchase=addslashes($x->PURCHASE);
					if($corpCode != "" || $labelCode != "" || $labelProductOfferCode != "" || $reportingId != "" || $exclusiveInd != "" || $purchase !="")
					{
						if(!mysql_query("insert into PRODUCT_OFFER(LABEL_PRODUCT_OFFER_CODE,CORP_CODE,LABEL_CODE,REPORTING_ID,PURCHASE,ProdID,EXCLUSIVE_IND)values('$labelProductOfferCode','$corpCode','$labelCode','$reportingId','$purchase','$prodId','$exclusiveInd')"))
						{
							$error=1;
						}
						$productOfferId = mysql_insert_id();
						//SALES_TERRITORY
						$priceCategory=addslashes($x->SALES_TERRITORY->PRICE_CATEGORY);
						$territoryCode=addslashes($x->SALES_TERRITORY->TERRITORY_CODE);
						$salesStartsDate=addslashes($x->SALES_TERRITORY->SALES_START_DATE);
						$salesEndDate=addslashes($x->SALES_TERRITORY->SALES_END_DATE);
						if($priceCategory != "" || $territoryCode != "" || $salesStartsDate != "" || $salesEndDate != "")
						{
							if(!mysql_query("insert into SALES_TERRITORY(PRICE_CATEGORY,TERRITORY_CODE,SALES_START_DATE,SALES_END_DATE,PRODUCT_OFFER_ID)values('$priceCategory','$territoryCode','$salesStartsDate','$salesEndDate','$productOfferId')")){
								$error=1;
							}
						}
						$salesTerritoryId=mysql_insert_id();
						//PRICING
						$currencyCode=addslashes($x->SALES_TERRITORY->PRICING->CURRENCY_CODE);
						$wholeSalePrice=addslashes($x->SALES_TERRITORY->PRICING->WHOLE_SALE_PRICE);
						$suggestedRetailPrice=addslashes($node->Product->PRODUCT_OFFER->SALES_TERRITORY->PRICING->SUGGESTED_RETAIL_PRICE);
						if($currencyCode !="" || $wholeSalePrice !="" || $suggestedRetailPrice != "")
						{
							if(!mysql_query("insert into PRICING(SALES_TERRITORY_ID,CURRENCY_CODE,WHOLE_SALE_PRICE,SUGGESTED_RETAIL_PRICE)values('$salesTerritoryId','$currencyCode','$wholeSalePrice','$suggestedRetailPrice')")){
								$error=1;
							}
						}
						//retailer
						$labelRetailerCode=addslashes($x->SALES_TERRITORY->RETAILER->LABEL_RETAILER_CODE);
						$discountCurrency=addslashes($x->SALES_TERRITORY->RETAILER->DISCOUNT_CURRENCY);
						$discountType=addslashes($x->SALES_TERRITORY->RETAILER->DISCOUNT_TYPE);
						$value=addslashes($x->SALES_TERRITORY->RETAILER->VALUE);
						if($labelRetailerCode !="" || $discountCurrency != "" || $discountType != "" || $value != "")
						{
							if(!mysql_query("insert into RETAILER(SALES_TERRITORY_ID,DISCOUNT_TYPE,LABEL_RETAILER_CODE,DISCOUNT_CURRENCY,DISCOUNT_VALUE)values('$salesTerritoryId','$discountType','$labelRetailerCode','$discountCurrency','$value')")){
								$error=1;
							}
						}
					}
					else
					{
						//does nthing
					}
				}
			}
			else
			{
				$getProductOfferID = mysql_query("select  PRODUCT_OFFER_ID from PRODUCT_OFFER where ProdID='$prodId'");
				while($row = mysql_fetch_array($getProductOfferID))
				{
					$productOfferId=$row['PRODUCT_OFFER_ID'];
					$getSalesTerritoryID = mysql_query("select SALES_TERRITORY_ID from SALES_TERRITORY where PRODUCT_OFFER_ID='$productOfferId'");
					while($row = mysql_fetch_array($getSalesTerritoryID))
					{
						$salesTerritoryId = $row['SALES_TERRITORY_ID'];
						if(!mysql_query("delete from RETAILER where SALES_TERRITORY_ID='$salesTerritoryId'"))
						{
							$error=1;
						}
						if(!mysql_query("delete from PRICING where SALES_TERRITORY_ID='$salesTerritoryId'"))
						{
							$error=1;
						}

					}
					if(!mysql_query("delete from SALES_TERRITORY where PRODUCT_OFFER_ID='$productOfferId'"))
					{
						$error=1;
					}
				}
				if(!mysql_query("delete from PRODUCT_OFFER where ProdID='$prodId'"))
				{
					$error=1;
				}
			}

			//Graphic
			foreach($xml->Action->Product->Graphic as $x)
			{
				$imgFormat=addslashes($x->ImgFormat);
				$imgWidth=addslashes($x->ImgWidth);
				$imgHeight=addslashes($x->ImgHeight);
				//File
				$sourceUrl=addslashes($x->File->SourceURL);
				$hostUrl=addslashes($x->File->HostURL);
				if(trim($hostUrl) == "")
				{
					$hostUrl = HOST_URL;
				}
				$saveAsName=addslashes($x->File->SaveAsName);
				$digitalSignature=addslashes($x->File->DigitalSignature);
				$cdnPath = pathToCdn($prodId);
				if(!mysql_query("insert into File(SourceURL,HostURL,SaveAsName,DigitalSignature,CdnPath)values('$sourceUrl','$hostUrl','$saveAsName','$digitalSignature','$cdnPath')")){
					$error=1;
				}
				$fileIdQuery = mysql_query("select FileID from Audio where TrkID='$trkId' AND Bitrate='$audioBitRate'");
				$row = mysql_fetch_array($fileIdQuery);
				$fileId = $row['FileID'];
				$fileCompletePath = str_replace($xmlFileName,$sourceUrl,$file);
				if(trim($sourceUrl) != "")
				{
					$fsize = filesize(ROOTPATH.$fileCompletePath);
					fwrite($fh,$fileCompletePath."\t".$date."\t".$fsize."\n");
				}
				if($sourceUrl != "")
				{
					if(!mysql_query("update File set SourceURL='$sourceUrl',HostURL='$hostUrl',SaveAsName='$saveAsName',DigitalSignature='$digitalSignature' where FileID='$fileId'"))
					{
						$error=1;
					}
					}else{
						mysql_query("update File set HostURL='$hostUrl',SaveAsName='$saveAsName',DigitalSignature='$digitalSignature' where FileID='$fileId'");
					}

					if(trim($saveAsName) != "" && $sourceUrl != "" )
					{
						$sourceUrl = $saveAsName;
						sendFile(ROOTPATH.$fileCompletePath,$sourceUrl,$prodId,$logFileWrite);
					}
					if($sourceUrl != "" && trim($saveAsName) == "")
					{
						sendFile(ROOTPATH.$fileCompletePath,$sourceUrl,$prodId,$logFileWrite);
					}
					if(!mysql_query("update Graphic set ImgFormat='$imgFormat',ImgWidth='$imgWidth',ImgHeight='$imgHeight',FileID='$fileId' where ProdID='$prodId'")){
						$error=1;
					}
				}
				//Metadata
				$title=addslashes($node->Product->MetaData->Title);
				$artist=addslashes($node->Product->MetaData->Artist);
				$copyright=addslashes($node->Product->MetaData->Copyright);
				$year=addslashes($node->Product->MetaData->Year);
				//MetaData->Genre=
				$genreName=addslashes($node->Product->MetaData->Genre['name'][0]);
				$subGenreName=addslashes($node->Product->MetaData->Genre->Subgenre['name'][0]);
				if(!mysql_query("update Genre set Genre='$genreName',Subgenre='$subGenreName' where ProdID='$prodId'")){
					$error=1;
				}
				$advisory=addslashes($node->Product->MetaData->Advisory);
				$provider=addslashes($node->Product->MetaData->Provider);
				$label=addslashes($node->Product->MetaData->Label);
				if(!mysql_query("delete from Participant where ProdID='$prodId'")){
					$error=1;
				}
				foreach($xml->Action->Product->MetaData->Participant as $x)
				{
					$participantName=addslashes($x['name'][0]);
					$participantRole=addslashes($x['role'][0]);
					if(!mysql_query("insert into Participant(ProdID,Role,Name)values('$prodId','$participantRole','$participantName')")){
						$error=1;
					}
				}
				$producer=addslashes($node->Product->MetaData->Producer);
				$artistUrl=addslashes($node->Product->MetaData->ArtistURL);
				$artistLinkText=addslashes($node->Product->MetaData->ArtistLinkText);
				$artistInfoDetail=addslashes($node->Product->MetaData->ArtistInfoDetail);
				$productUrl=addslashes($node->Product->MetaData->ProductUrl);
				$productLinkText=addslashes($node->Product->MetaData->ProductLinkText);
				$productInfoDetail=addslashes($node->Product->MetaData->ProductInfoDetail);
				//Physical Product..
				$physicalProductTitle=addslashes($node->Product->MetaData->PhysicalProduct->Title);
				$referenceId=addslashes($node->Product->MetaData->PhysicalProduct->ReferenceID);
				$productCode=addslashes($node->Product->MetaData->PhysicalProduct->ProductCode);
				$artistText=addslashes($node->Product->MetaData->PhysicalProduct->ArtistText);
				$mediaCount=addslashes($node->Product->MetaData->PhysicalProduct->MediaCount);
				$trackBundleCount=addslashes($node->Product->MetaData->PhysicalProduct->TrackBundleCount);
				$trackCount=addslashes($node->Product->MetaData->PhysicalProduct->TrackCount);
				$releaseDate=addslashes($node->Product->MetaData->PhysicalProduct->ReleaseDate);
				$physicalProductId=addslashes($node->Product->MetaData->PhysicalProduct->ProductID);
				$mediaNo=addslashes($node->Product->MetaData->PhysicalProduct->MediaNo);
				$trackNo=addslashes($node->Product->MetaData->PhysicalProduct->TrackNo);
				$version=addslashes($node->Product->MetaData->PhysicalProduct->Version);


				foreach($xml->Action->Product->Track as $x)
				{
					$trkId=addslashes($x->TrkID);
					$sequenceNum=addslashes($x->SequenceNum);
					if(!mysql_query("update TRACK set SequenceNum='$sequenceNum' where TrkID='$trkId'")){
						$error=1;
					}
				}
				if(!mysql_query("update  PhysicalProduct set ProdID='$prodId',ReferenceID='$referenceId',Title='$physicalProductTitle',ArtistText='$artistText',MediaCount='$mediaCount',TrackBundleCount='$trackBundleCount',TrackCount='$trackCount',ReleaseDate='$releaseDate',ProductID='$physicalProductId',MediaNo='$mediaNo',TrackNo='$trackNo',Version='$version' where ProdID='$prodId'")){
					$error=1;
				}

				if(!mysql_query("update METADATA set ProdID='$prodId',Title='$title',Version='$version',Artist='$artist',Copyright='$copyright',Year='$year',Advisory='$advisory',Provider='$provider',Label='$label',Producer='$producer',ArtistURL='$artistUrl',ArtistLinkText='$artistLinkText',ArtistInfoDetail='$artistInfoDetail',ProductURL='$productUrl',ProductInfoDetail='$productInfoDetail',ProductLinkText='$productLinkText' where ProdID='$prodId'")){
					$error=1;
				}
				fwrite($fh,$file."\t".$date."\t".$fsize."\n");
			}
		}
	}


/**
Method Name : trackTypeUpdate
Desc : Parses the Track type XMl and Updates the DB
**/

function trackTypeUpdate($file,$fh,$logFileWrite)
{
	//creating log file
	$date = date("F j, Y, g:i a");
	$fsize=filesize(ROOTPATH.$file);
	$xmlFileName = explode("/",ROOTPATH.$file);
	$xmlFileName  = $xmlFileName[count($xmlFileName)-1];
	$xmlstr=file_get_contents(ROOTPATH.$file);
	$xml=simplexml_load_string($xmlstr);
	foreach($xml->Action as $node)
	{
		$productType=addslashes($node->Product['Type'][0]);
		$typeName=addslashes($node->Product->TypeName);
		$prodId=addslashes($node->Product->ProdID);
		$result = mysql_query("select ProdID from PRODUCT where ProdID='$prodId'");
		$num_rows = mysql_num_rows($result);
		if($num_rows == 0)
		{
			trackTypeInsert($file,$fh,$logFileWrite);
		}else{
		
			if(!mysql_query("delete from Availability where ProdID='$prodId'")){
				$error=1;
			}
			foreach($xml->Action->Product->Availability as $t)
			{
				$availabilityType=addslashes($t['Type'][0]);
				$availabilityStatus=addslashes($t['Status'][0]);
				if(!mysql_query("insert into Availability(ProdID,AvailabilityType,AvailabilityStatus)values('$prodId','$availabilityType','$availabilityStatus')")){
					$error=1;
				}
			}
			//PRODUCT_OFFER

			/*Deleteing the product Offer values fro DB*/
			$getProductOfferID = mysql_query("select  PRODUCT_OFFER_ID from PRODUCT_OFFER where ProdID='$prodId'");
			while($row = mysql_fetch_array($getProductOfferID))
			{
				$productOfferId=$row['PRODUCT_OFFER_ID'];
				$getSalesTerritoryID = mysql_query("select SALES_TERRITORY_ID from SALES_TERRITORY where PRODUCT_OFFER_ID='$productOfferId'");
				while($row = mysql_fetch_array($getSalesTerritoryID))
				{
					$salesTerritoryId = $row['SALES_TERRITORY_ID'];
					if(!mysql_query("delete from RETAILER where SALES_TERRITORY_ID='$salesTerritoryId'"))
					{
						$error=1;
					}
					if(!mysql_query("delete from PRICING where SALES_TERRITORY_ID='$salesTerritoryId'"))
					{
						$error=1;
					}
				}
				if(!mysql_query("delete from SALES_TERRITORY where PRODUCT_OFFER_ID='$productOfferId'"))
				{
					$error=1;
				}
			}
			if(!mysql_query("delete from PRODUCT_OFFER where ProdID='$prodId'"))
			{
				$error=1;
			}
			/*end of deleting*/





			if(count($xml->Action->Product->PRODUCT_OFFER) != 0)
			{
				echo count($xml->Action->Product->PRODUCT_OFFER);
				foreach($xml->Action->Product->PRODUCT_OFFER as $x)
				{
					echo count($xml->Action->Product->PRODUCT_OFFER)."\n";
					$corpCode=addslashes($x->CORP_CODE);
					$labelCode=addslashes($x->LABEL_CODE);
					$labelProductOfferCode=addslashes($x->LABEL_PRODUCT_OFFER_CODE);
					$reportingId=addslashes($x->REPORTING_ID);
					$exclusiveInd=addslashes($x->EXCLUSIVE_IND);
					$purchase=addslashes($x->PURCHASE);
					if($corpCode != "" || $labelCode != "" || $labelProductOfferCode != "" || $reportingId != "" || $exclusiveInd != "" || $purchase !="")
					{
						if(!mysql_query("insert into PRODUCT_OFFER(LABEL_PRODUCT_OFFER_CODE,CORP_CODE,LABEL_CODE,REPORTING_ID,PURCHASE,ProdID,EXCLUSIVE_IND)values('$labelProductOfferCode','$corpCode','$labelCode','$reportingId','$purchase','$prodId','$exclusiveInd')"))
						{
							$error=1;
						}
						$productOfferId = mysql_insert_id();
						//SALES_TERRITORY
						$priceCategory=addslashes($x->SALES_TERRITORY->PRICE_CATEGORY);
						$territoryCode=addslashes($x->SALES_TERRITORY->TERRITORY_CODE);
						$salesStartsDate=addslashes($x->SALES_TERRITORY->SALES_START_DATE);
						$salesEndDate=addslashes($x->SALES_TERRITORY->SALES_END_DATE);
						if($priceCategory != "" || $territoryCode != "" || $salesStartsDate != "" || $salesEndDate != "")
						{
							if(!mysql_query("insert into SALES_TERRITORY(PRICE_CATEGORY,TERRITORY_CODE,SALES_START_DATE,SALES_END_DATE,PRODUCT_OFFER_ID)values('$priceCategory','$territoryCode','$salesStartsDate','$salesEndDate','$productOfferId')")){
								$error=1;
							}
						}
						$salesTerritoryId=mysql_insert_id();
						//PRICING
						$currencyCode=addslashes($x->SALES_TERRITORY->PRICING->CURRENCY_CODE);
						$wholeSalePrice=addslashes($x->SALES_TERRITORY->PRICING->WHOLE_SALE_PRICE);
						$suggestedRetailPrice=addslashes($node->Product->PRODUCT_OFFER->SALES_TERRITORY->PRICING->SUGGESTED_RETAIL_PRICE);
						if($currencyCode !="" || $wholeSalePrice !="" || $suggestedRetailPrice != "")
						{
							if(!mysql_query("insert into PRICING(SALES_TERRITORY_ID,CURRENCY_CODE,WHOLE_SALE_PRICE,SUGGESTED_RETAIL_PRICE)values('$salesTerritoryId','$currencyCode','$wholeSalePrice','$suggestedRetailPrice')")){
								$error=1;
							}
						}
						//retailer
						$labelRetailerCode=addslashes($x->SALES_TERRITORY->RETAILER->LABEL_RETAILER_CODE);
						$discountCurrency=addslashes($x->SALES_TERRITORY->RETAILER->DISCOUNT_CURRENCY);
						$discountType=addslashes($x->SALES_TERRITORY->RETAILER->DISCOUNT_TYPE);
						$value=addslashes($x->SALES_TERRITORY->RETAILER->VALUE);
						if($labelRetailerCode !="" || $discountCurrency != "" || $discountType != "" || $value != "")
						{
							if(!mysql_query("insert into RETAILER(SALES_TERRITORY_ID,DISCOUNT_TYPE,LABEL_RETAILER_CODE,DISCOUNT_CURRENCY,DISCOUNT_VALUE)values('$salesTerritoryId','$discountType','$labelRetailerCode','$discountCurrency','$value')")){
								$error=1;
							}
						}
					}
					else
					{
						//does nothing
					}
				}
			}
			else
			{
				$getProductOfferID = mysql_query("select  PRODUCT_OFFER_ID from PRODUCT_OFFER where ProdID='$prodId'");
				while($row = mysql_fetch_array($getProductOfferID))
				{
					$productOfferId=$row['PRODUCT_OFFER_ID'];
					$getSalesTerritoryID = mysql_query("select SALES_TERRITORY_ID from SALES_TERRITORY where PRODUCT_OFFER_ID='$productOfferId'");
					while($row = mysql_fetch_array($getSalesTerritoryID))
					{
						$salesTerritoryId = $row['SALES_TERRITORY_ID'];
						if(!mysql_query("delete from RETAILER where SALES_TERRITORY_ID='$salesTerritoryId'"))
						{
							$error=1;
						}
						if(!mysql_query("delete from PRICING where SALES_TERRITORY_ID='$salesTerritoryId'"))
						{
							$error=1;
						}

					}
					if(!mysql_query("delete from SALES_TERRITORY where PRODUCT_OFFER_ID='$productOfferId'"))
					{
						$error=1;
					}
				}
				if(!mysql_query("delete from PRODUCT_OFFER where ProdID='$prodId'"))
				{
					$error=1;
				}
			}
			$trkId=addslashes($node->Product->Track->TrkID);
			$seqNum=addslashes($node->Product->Track->SequenceNum);
			if(!mysql_query("update TRACK set SequenceNum='$sequenceNum' where TrkID='$trkId'")){
				$error=1;
			}
			//AudioSampleClip
			foreach($xml->Action->Product->Track->AudioSampleClip as $x)
			{
				$audioName=addslashes($x->AudioName);
				$audioType=addslashes($x->AudioType);
				$isClip=addslashes($x->IsClip);
				$audioCodec=addslashes($x->Codec);
				$audioBitRate=addslashes($x->BitRate);
				$audioNumChannels=addslashes($x->NumChannels);
				$audioSampleRate=addslashes($x->SampleRate);
				$audioBitPerSample=addslashes($x->BitPerSample);
				$audioDuration=addslashes($x->Duration);
				$clipOffsetStart=addslashes($x->ClipOffsetStart);
				$clipOffsetEnd=addslashes($x->ClipOffsetEnd);
				$sourceUrl=addslashes($x->File->SourceURL);
				$hostUrl=addslashes($x->File->HostURL);
				if(trim($hostUrl) == "")
				{
					$hostUrl = HOST_URL;
				}
				$saveAsName=addslashes($x->File->SaveAsName);
				$digitalSignature=addslashes($x->File->DigitalSignature);

				//$fileId= mysql_insert_id();
				$fileCompletePath = str_replace($xmlFileName,$sourceUrl,$file);
				if(trim($sourceUrl) != "")
				{
					$fsize = filesize(ROOTPATH.$fileCompletePath);
					
					echo $fileCompletePath."\t".$date."\t".$fsize."\n";
					
					fwrite($fh,$fileCompletePath."\t".$date."\t".$fsize."\n");
				}
				if(!mysql_query("update Audio set AudioName='$audioName',AudioType='$audioType',IsClip='$isClip',CODEC='$audioCodec',NumChannels='$audioNumChannels',SampleRate='$audioSampleRate',BitPerSample='$audioBitPerSample',Duration='$audioDuration',ClipOffsetStart='$clipOffsetStart',ClipOffsetEnd='$clipOffsetEnd' where TrkID='$trkId' AND Bitrate='$audioBitRate'"))
				{
					$error=1;
				}
				$fileIdQuery = mysql_query("select FileID from Audio where TrkID='$trkId' AND Bitrate='$audioBitRate'");
				$row = mysql_fetch_array($fileIdQuery);
				$fileId = $row['FileID'];
				if($sourceUrl != "")
				{
					if(!mysql_query("update File set SourceURL='$sourceUrl',HostURL='$hostUrl',SaveAsName='$saveAsName',DigitalSignature='$digitalSignature' where FileID='$fileId'"))
					{
						$error=1;
					}
					}else{
						mysql_query("update File set HostURL='$hostUrl',SaveAsName='$saveAsName',DigitalSignature='$digitalSignature' where FileID='$fileId'");
					}

					if(trim($saveAsName) != "" && $sourceUrl != "" )
					{
						$sourceUrl = $saveAsName;
						echo "=== Send file=====\n";
						echo ROOTPATH.$fileCompletePath;
						sendFile(ROOTPATH.$fileCompletePath,$sourceUrl,$prodId,$logFileWrite);
						echo "=== Send file=====\n";
					}
				}
				//AudioDownload
				foreach($xml->Action->Product->Track->AudioDownload as $x)
				{
					$audioName=addslashes($x->AudioName);
					$audioType=addslashes($x->AudioType);
					$isClip=addslashes($x->IsClip);
					$audioCodec=addslashes($x->Codec);
					$audioBitRate=addslashes($x->BitRate);
					$audioNumChannels=addslashes($x->NumChannels);
					$audioSampleRate=addslashes($x->SampleRate);
					$audioBitPerSample=addslashes($x->BitPerSample);
					$audioDuration=addslashes($x->Duration);
					$clipOffsetStart=addslashes($x->ClipOffsetStart);
					$clipOffsetEnd=addslashes($x->ClipOffsetEnd);
					$sourceUrl=addslashes($x->File->SourceURL);
					$hostUrl=addslashes($x->File->HostURL);
					if(trim($hostUrl) == "")
					{
						$hostUrl = HOST_URL;
					}
					$saveAsName=addslashes($x->File->SaveAsName);
					$digitalSignature=addslashes($x->File->DigitalSignature);

					//$fileId= mysql_insert_id();
					$fileCompletePath = str_replace($xmlFileName,$sourceUrl,$file);
					if(trim($sourceUrl) != "")
					{
						$fsize = filesize(ROOTPATH.$fileCompletePath);
						fwrite($fh,$fileCompletePath."\t".$date."\t".$fsize."\n");
					}
					if(!mysql_query("update Audio set AudioName='$audioName',AudioType='$audioType',IsClip='$isClip',CODEC='$audioCodec',NumChannels='$audioNumChannels',SampleRate='$audioSampleRate',BitPerSample='$audioBitPerSample',Duration='$audioDuration',ClipOffsetStart='$clipOffsetStart',ClipOffsetEnd='$clipOffsetEnd' where TrkID='$trkId' AND Bitrate='$audioBitRate'"))
					{
						$error=1;
					}
					$fileIdQuery = mysql_query("select FileID from Audio where TrkID='$trkId' AND Bitrate='$audioBitRate'");
					$row = mysql_fetch_array($fileIdQuery);
					$fileId = $row['FileID'];
					if($sourceUrl != "")
					{
						if(!mysql_query("update File set SourceURL='$sourceUrl',HostURL='$hostUrl',SaveAsName='$saveAsName',DigitalSignature='$digitalSignature' where FileID='$fileId'"))
						{
							$error=1;
						}
						}else{
							mysql_query("update File set HostURL='$hostUrl',SaveAsName='$saveAsName',DigitalSignature='$digitalSignature' where FileID='$fileId'");
						}

						if(trim($saveAsName) != "" && $sourceUrl != "" )
						{
							$sourceUrl = $saveAsName;
							sendFile(ROOTPATH.$fileCompletePath,$sourceUrl,$prodId,$logFileWrite);
						}
					}
					//Graphic
					foreach($xml->Action->Product->Track->Graphic as $x)
					{
						$imgFormat=addslashes($x->ImgFormat);
						$imgWidth=addslashes($x->ImgWidth);
						$imgHeight=addslashes($x->ImgHeight);
						//File
						$sourceUrl=addslashes($x->File->SourceURL);
						$hostUrl=addslashes($x->File->HostURL);
						if(trim($hostUrl) == "")
						{
							$hostUrl = HOST_URL;
						}
						$saveAsName=addslashes($x->File->SaveAsName);
						$digitalSignature=addslashes($x->File->DigitalSignature);
						$fileCompletePath = str_replace($xmlFileName,$sourceUrl,$file);
						if(trim($sourceUrl) != "")
						{
							$fsize = filesize(ROOTPATH.$fileCompletePath);
							fwrite($fh,$fileCompletePath."\t".$date."\t".$fsize."\n");
						}
						if(!mysql_query("update Graphic set ImgFormat='$imgFormat',ImgWidth='$imgWidth',ImgHeight='$imgHeight' where ProdID='$prodId'")){
							$error=1;
						}
						$fileIdQuery = mysql_query("select FileID from Graphic where ProdID='$prodId'");
						$row = mysql_fetch_array($fileIdQuery);
						$fileId = $row['FileID'];

						if($sourceUrl != "")
						{
							if(!mysql_query("update File set SourceURL='$sourceUrl',HostURL='$hostUrl',SaveAsName='$saveAsName',DigitalSignature='$digitalSignature' where FileID='$fileId'")){
								$error=1;
							}
							}else{
								mysql_query("update File set HostURL='$hostUrl',SaveAsName='$saveAsName',DigitalSignature='$digitalSignature' where FileID='$fileId'");
							}
							if(trim($saveAsName) != "" && $sourceUrl != "")
							{
								$sourceUrl = $saveAsName;
								sendFile(ROOTPATH.$fileCompletePath,$sourceUrl,$prodId,$logFileWrite);
							}
							if($sourceUrl != "" && trim($saveAsName) == "")
							{
								sendFile(ROOTPATH.$fileCompletePath,$sourceUrl,$prodId,$logFileWrite);
							}

						}
						//Metadata
						$title=addslashes($node->Product->Track->MetaData->Title);
						$version=addslashes($node->Product->Track->MetaData->Version);
						$artist=addslashes($node->Product->Track->MetaData->Artist);
						$isrc=addslashes($node->Product->Track->MetaData->ISRC);
						$copyright=addslashes($node->Product->Track->MetaData->Copyright);
						$year=addslashes($node->Product->Track->MetaData->Year);
						//MetaData->Genre
						$genreName=addslashes($node->Product->Track->MetaData->Genre['name'][0]);
						$subGenreName=addslashes($node->Product->Track->MetaData->Genre->Subgenre['name'][0]);
						if(!mysql_query("update Genre set ProdID='$prodId',Genre='$genreName',Subgenre='$subGenreName' where ProdID='$prodId'")){
							$error=1;
						}
						$advisory=addslashes($node->Product->Track->MetaData->Advisory);
						$provider=addslashes($node->Product->Track->MetaData->Provider);
						$genreLabel=addslashes($node->Product->Track->MetaData->Label);
						if(!mysql_query("delete from Participant where ProdID='$prodId'")){
							$error=1;
						}
						foreach($xml->Action->Product->Track->MetaData->Participant as $x)
						{
							$participantName=addslashes($x['name'][0]);
							$participantRole=addslashes($x['role'][0]);
							if(!mysql_query("insert into Participant(ProdID,Role,Name)values('$prodId','$participantRole','$participantName')")){
								$error=1;
							}
						}
						$producer=addslashes($node->Product->Track->MetaData->Producer);
						$publisher=addslashes($node->Product->Track->MetaData->Publisher);
						$songWriter=addslashes($node->Product->Track->MetaData->SongWriter);
						$artistUrl=addslashes($node->Product->Track->MetaData->ArtistURL);
						$artistLinkText=addslashes($node->Product->Track->MetaData->ArtistLinkText);
						$artistInfoDetail=addslashes($node->Product->Track->MetaData->ArtistInfoDetail);
						$productUrl=addslashes($node->Product->Track->MetaData->ProductUrl);
						$productLinkText=addslashes($node->Product->Track->MetaData->ProductLinkText);
						$productInfoDetail=addslashes($node->Product->Track->MetaData->ProductInfoDetail);
						//Physical Product
						$physicalProductTitle=addslashes($node->Product->Track->MetaData->PhysicalProduct->Title);
						$referenceId=addslashes($node->Product->Track->MetaData->PhysicalProduct->ReferenceID);
						$productCode=addslashes($node->Product->Track->MetaData->PhysicalProduct->ProductCode);
						$artistText=addslashes($node->Product->Track->MetaData->PhysicalProduct->ArtistText);
						$mediaCount=addslashes($node->Product->Track->MetaData->PhysicalProduct->MediaCount);
						$trackBundleCount=addslashes($node->Product->Track->MetaData->PhysicalProduct->TrackBundleCount);
						$trackCount=addslashes($node->Product->Track->MetaData->PhysicalProduct->TrackCount);
						$releaseDate=addslashes($node->Product->Track->MetaData->PhysicalProduct->ReleaseDate);
						$physicalProductId=addslashes($node->Product->Track->MetaData->PhysicalProduct->ProductID);
						$mediaNo=addslashes($node->Product->Track->MetaData->PhysicalProduct->MediaNo);
						$trackNo=addslashes($node->Product->Track->MetaData->PhysicalProduct->TrackNo);
						$hiddenTrackIndicator=addslashes($node->Product->Track->MetaData->PhysicalProduct->HiddenTrackIndicator);
						$songSequenceNo=addslashes($node->Product->Track->MetaData->PhysicalProduct->SongSequenceNo);
						$physicalProductVersion=addslashes($node->Product->Track->MetaData->PhysicalProduct->Version);

						//TrackBundle
						$trackBundleTitle=addslashes($node->Product->Track->MetaData->TrackBundle->Title);
						$trackBundleReferenceId=addslashes($node->Product->Track->MetaData->TrackBundle->ReferenceID);
						$trackBundleSequenceNo=addslashes($node->Product->Track->MetaData->TrackBundle->SequenceNo);
						$trackBundleTrackCount=addslashes($node->Product->Track->MetaData->TrackBundle->TrackCount);
						//if($trackBundleTitle != "" || $trackBundleReferenceId != "" || $trackBundleSequenceNo != "" || $trackBundleTrackCount != "" ){
						if(!mysql_query("update TrackBundle set Title='$trackBundleTitle',ReferenceID='$trackBundleReferenceId',SequenceNo='$trackBundleSequenceNo',TrackCountvalues='$trackBundleTrackCount' where ProdID='$prodId'")){
							$error=1;
							//}
						}
						if(!mysql_query("update  PhysicalProduct set ReferenceID='$referenceId',Title='$physicalProductTitle',ArtistText='$artistText',MediaCount='$mediaCount',TrackBundleCount='$trackBundleCount',TrackCount='$trackCount',ReleaseDate='$releaseDate',ProductID='$physicalProductId',MediaNo='$mediaNo',TrackNo='$trackNo',Version='$version' where ProdID='$prodId'")){
							$error=1;
						}

						if(!mysql_query("update METADATA set Title='$title',Version='$version',Artist='$artist',Copyright='$copyright',Year='$year',Advisory='$advisory',Provider='$provider',Label='$label',Producer='$producer',ArtistURL='$artistUrl',ArtistLinkText='$artistLinkText',ArtistInfoDetail='$artistInfoDetail',ProductURL='$productUrl',ProductInfoDetail='$productInfoDetail',ProductLinkText='$productLinkText' where ProdID='$prodId'")){
							$error=1;
						}
						fwrite($fh,$file."\t".$date."\t".$fsize."\n");
					}
				}
			}
								

/**
Method Name : collectionTypeDelete
Desc : Parses the Collection type XMl and Updates the DB
**/
function collectionTypeDelete($file,$fh,$logFileWrite)
{
	//creating log file
	$date = date("F j, Y, g:i a");
	$fsize=filesize(ROOTPATH.$file);
	$xmlFileName = explode("/",ROOTPATH.$file);
	$xmlFileName  = $xmlFileName[count($xmlFileName)-1];
	$xmlstr=file_get_contents(ROOTPATH.$file);
	$xml=simplexml_load_string($xmlstr);
	$actionType=$xml->Action['Type'][0];
	foreach($xml->Action as $node)
	{
		$productType=addslashes($node->Product['Type'][0]);
		$productTypeName=addslashes($node->Product->TypeName);
		$prodId=addslashes($node->Product->ProdID);
		foreach($xml->Action->Product as $t)
		{
			$availabilityType=addslashes($t->Availability['Type'][0]);
			$availabilityStatus=addslashes($t->Availability['Status'][0]);
			if(!mysql_query("update Availability set AvailabilityStatus='D' where ProdID='$prodId'"))
			{
				$error=1;
			}
		}
	}
	fwrite($fh,$file."\t".$date."\t".$fsize."\n");
}

/**
Method Name : trackTypeDelete
Desc : Parses the Track type XMl and Updates the DB
**/
function trackTypeDelete($file,$fh,$logFileWrite)
{
	//creating log file
	$date = date("F j, Y, g:i a");
	$fsize=filesize(ROOTPATH.$file);
	$xmlFileName = explode("/",ROOTPATH.$file);
	$xmlFileName  = $xmlFileName[count($xmlFileName)-1];
	//	fwrite($fh,$file."\t".$date."\t".$fsize."\n");
	$xmlstr=file_get_contents(ROOTPATH.$file);
	$xml=simplexml_load_string($xmlstr);
	foreach($xml->Action as $node)
	{
		$productType=addslashes($node->Product['Type'][0]);
		$typeName=addslashes($node->Product->TypeName);
		$prodId=addslashes($node->Product->ProdID);
		foreach($xml->Action->Product->Availability as $x)
		{
			$availabilityType=addslashes($x['Type'][0]);
			$availabilityStatus=addslashes($x['Status'][0]);
			if(mysql_query("update Availability set AvailabilityStatus='D' where ProdID='$prodId'"))
			{
				$error=1;
			}
		}
	}
	fwrite($fh,$file."\t".$date."\t".$fsize."\n");
}


/**
Method Name : sendFile
Desc : Takes the source ad destnation path as input parameters and moves the files to the CDN server using SFTP
**/

function sendFile($src,$dst,$prodId,$logFileWrite)
{
	if(!($con = ssh2_connect(SFTP_HOST,SFTP_PORT)))
	{
		echo "Not Able to Establish Connection\n";
	}
	else
	{
		if(!ssh2_auth_password($con,SFTP_USER,SFTP_PASS))
		{
			echo "fail: unable to authenticate\n";
		}
		else
		{
			$sftp = ssh2_sftp($con);
			//$dirName =  $fileId % 1000;
			$dirName = createPath($sftp,$prodId);

			/*if(!is_dir("ssh2.sftp://$sftp".CDNPATH."/".$dirName))
			{
			ssh2_sftp_mkdir($sftp,CDNPATH.$dirName);
			}*/
			if(!ssh2_scp_send($con, $src, CDNPATH."/".$dirName."/".$dst, 0644)){
				echo "error\n";
				fwrite($logFileWrite, "error\n");
			}
			else
			{
				echo "File Sucessfully sent\n";
				fwrite($logFileWrite, "File Sucessfully sent\n");
			}
		}
	}
}

function createPath($sftp,$prodId)
{
	for($i=0;$i<=20;$i=$i+3)
	{
		$x=$prodId;
		$dir=substr($x,$i,3);
		if(strlen($dir) == 3)
		{
			$dirpath.= $dir."/";
			if(!is_dir("ssh2.sftp://$sftp".CDNPATH."/".$dirpath))
			{
				ssh2_sftp_mkdir($sftp,CDNPATH.$dirpath);
			}
		}
		else
		{
			$d = substr($x,-2);
			$dirpath.= $d;
			if(!is_dir("ssh2.sftp://$sftp".CDNPATH."/".$dirpath))
			{
				ssh2_sftp_mkdir($sftp,CDNPATH.$dirpath);
			}
		}
	}
	return  $dirpath;
}

function pathToCdn($prodId)
{
	for($i=0;$i<=20;$i=$i+3)
	{
		$x=$prodId;
		$dir=substr($x,$i,3);
		if(strlen($dir) == 3)
		{
			$dirpath.= $dir."/";
		}
		else
		{
			$d = substr($x,-2);
			$dirpath.= $d;
		}
	}
	return $dirpath;
}

/**
Method Name : validate
Desc : validate the XML file.
**/

function validate($xml)
{
	$file = $xml;
	$xmlstr=file_get_contents(ROOTPATH.$xml);
	$xml=simplexml_load_string($xmlstr);
	$actionType=$xml->Action['Type'][0];
	$productType=$xml->Action->Product['Type'][0];
	if($actionType == "INSERT" && $productType == "COLLECTION")
	{
		$flag=true;
		foreach($xml->Action->Product->Graphic as $x)
		{
			if($flag ==true)
			{
				$sourceUrl=$x->File->SourceURL;
				$digitalSignature=$x->File->DigitalSignature;
				if($sourceUrl == "")
				{
					$flag = false;
					return "source Url is blank\n";
				}
				else
				{
					$xmlFileName = explode("/",ROOTPATH.$file);
					$mediaFileCompletePath = str_replace($xmlFileName[count($xmlFileName)-1],$sourceUrl,ROOTPATH.$file);
					$mediaPath=$mediaFileCompletePath;
					if(!file_exists($mediaPath))
					{
						$flag = false;
						return "media file not found -- (".$mediaPath.")\n";
					}
					else
					{
						$verifySignature = md5_file($mediaPath);
						if($digitalSignature == $verifySignature)
						{
							$flag = true;
	
						}
						else
						{
							$flag = false;
							return "Invalid Signature -- (".$mediaPath.")\n";
						}
					}
				}
			}
	
		}
		if($flag == false)
		{
			$flag = 0;
			}else{
				$flag = 1;
			}
			return $flag;
		}
		if($actionType == "INSERT" && $productType == "TRACK")
		{
			$flag=true;
			foreach($xml->Action->Product->Track->AudioSampleClip as $x)
			{
				if($flag==true)
				{
					$sourceUrl=$x->File->SourceURL;
					//echo $sourceUrl."\n";
					$digitalSignature=$x->File->DigitalSignature;
					//echo "sign: ".$digitalSignature."\n";
					if($sourceUrl == "")
					{
						$flag = false;
						return "source Url is blank\n";
					}
					else
					{
						$xmlFileName = explode("/",ROOTPATH.$file);
						$mediaFileCompletePath = str_replace($xmlFileName[count($xmlFileName)-1],$sourceUrl,ROOTPATH.$file);
						$mediaPath=$mediaFileCompletePath;
						if(!file_exists($mediaPath))
						{
							$flag = false;
							return "media file not found -- (".$mediaPath.")\n";
						}
						else
						{
							$verifySignature = md5_file($mediaPath);
							if($digitalSignature == $verifySignature)
							{
								$flag = true;
							}
							else
							{
								$flag = false;
								return "Invalid signature-- (".$mediaPath.")\n";
							}
						}
					}
				}
			}
			if($flag == true)
			{
				foreach($xml->Action->Product->Track->AudioDownload as $x)
				{
					if($flag==true)
					{
						$sourceUrl=$x->File->SourceURL;
						$digitalSignature=$x->File->DigitalSignature;
						if($sourceUrl == "")
						{
							$flag = false;
							return "source Url is blank\n";
						}
						else
						{
							$xmlFileName = explode("/",ROOTPATH.$file);
							$mediaFileCompletePath = str_replace($xmlFileName[count($xmlFileName)-1],$sourceUrl,ROOTPATH.$file);
							$mediaPath=$mediaFileCompletePath;
							if(!file_exists($mediaPath))
							{
								$flag = false;
								return "media file not found -- (".$mediaPath.")\n";
							}
							else
							{
								$verifySignature = md5_file($mediaPath);
								if($digitalSignature == $verifySignature)
								{
									$flag = true;
								}
								else
								{
									$flag = false;
									return "Invalid signature-- (".$mediaPath.")\n";
								}
							}
						}
					}
				}
				if($flag == true)
				{
					foreach($xml->Action->Product->Track->Graphic as $x)
					{
						if($flag==true)
						{
							$sourceUrl=$x->File->SourceURL;
							$digitalSignature=$x->File->DigitalSignature;
							if($sourceUrl == "")
							{
								$flag = false;
								return "source Url is blank\n";
							}
							else
							{
								$xmlFileName = explode("/",ROOTPATH.$file);
								$mediaFileCompletePath = str_replace($xmlFileName[count($xmlFileName)-1],$sourceUrl,ROOTPATH.$file);
								$mediaPath=$mediaFileCompletePath;
								if(!file_exists($mediaPath))
								{
									$flag = false;
									return "media file not found -- (".$mediaPath.")\n";
								}
								else
								{
									$verifySignature = md5_file($mediaPath);
									if($digitalSignature == $verifySignature)
									{
										$flag = true;
	
									}
									else
									{
										$flag = false;
										return "Invalid signature-- (".$mediaPath.")\n";
									}
								}
							}
						}
					}
				}
			}
			if($flag == false)
			{
				$flag = 0;
				}else{
					$flag = 1;
				}
				return $flag;
		}
			if($actionType == "UPDATE" && $productType == "COLLECTION")
			{
				$flag=true;
				foreach($xml->Action->Product->Graphic as $x)
				{
					if($flag ==true)
					{
						$sourceUrl=$x->File->SourceURL;
						$digitalSignature=$x->File->DigitalSignature;
						if($sourceUrl == "")
						{
							//$flag = false;
							//return "source Url is blank\n";
							$flag = true;
						}
						else
						{
							$xmlFileName = explode("/",ROOTPATH.$file);
							$mediaFileCompletePath = str_replace($xmlFileName[count($xmlFileName)-1],$sourceUrl,ROOTPATH.$file);
							$mediaPath=$mediaFileCompletePath;
							if(!file_exists($mediaPath))
							{
								$flag = true;
								//return "medisa file not found\n";
							}
							else
							{
								$verifySignature = md5_file($mediaPath);
								if($digitalSignature == $verifySignature)
								{
									$flag = true;
	
								}
								else
								{
									$flag = false;
									return "Invalid signature-- (".$mediaPath.")\n";
								}
							}
						}
					}
	
				}
				if($flag == false)
				{
					$flag = 0;
					}else{
						$flag = 1;
					}
					return $flag;
			}
	
				if($actionType == "UPDATE" && $productType == "TRACK")
				{
					$flag=true;
					foreach($xml->Action->Product->Track->AudioSampleClip as $x)
					{
						if($flag==true)
						{
							$sourceUrl=$x->File->SourceURL;
							// echo $sourceUrl."\n";
							$digitalSignature=$x->File->DigitalSignature;
							// echo "sign: ".$digitalSignature."\n";
							if($sourceUrl == "")
							{
								//$flag = false;
								//return "source Url is blank\n";
								$flag = true;
							}
							else
							{
								$xmlFileName = explode("/",ROOTPATH.$file);
								$mediaFileCompletePath = str_replace($xmlFileName[count($xmlFileName)-1],$sourceUrl,ROOTPATH.$file);
								$mediaPath=$mediaFileCompletePath;
								if(!file_exists($mediaPath))
								{
									$flag = true;
									//return "no file found\n";
								}
								else
								{
									$verifySignature = md5_file($mediaPath);
									if($digitalSignature == $verifySignature)
									{
										$flag = true;
									}
									else
									{
										$flag = false;
										return "Invalid signature-- (".$mediaPath.")\n";
									}
								}
							}
						}
					}
					if($flag == true)
					{
						foreach($xml->Action->Product->Track->AudioDownload as $x)
						{
							if($flag==true)
							{
								$sourceUrl=$x->File->SourceURL;
								$digitalSignature=$x->File->DigitalSignature;
								if($sourceUrl == "")
								{
									$flag = true;
								}
								else
								{
									$xmlFileName = explode("/",ROOTPATH.$file);
									$mediaFileCompletePath = str_replace($xmlFileName[count($xmlFileName)-1],$sourceUrl,ROOTPATH.$file);
									$mediaPath=$mediaFileCompletePath;
									if(!file_exists($mediaPath))
									{
										$flag = true;
									}
									else
									{
										$verifySignature = md5_file($mediaPath);
										if($digitalSignature == $verifySignature)
										{
											$flag = true;
										}
										else
										{
											$flag = false;
											return "Invalid signature-- (".$mediaPath.")\n";
										}
									}
								}
							}
						}
						if($flag == true)
						{
							foreach($xml->Action->Product->Track->Graphic as $x)
							{
								if($flag==true)
								{
									$sourceUrl=$x->File->SourceURL;
									$digitalSignature=$x->File->DigitalSignature;
									if($sourceUrl == "")
									{
										$flag = True;
									}
									else
									{
										$xmlFileName = explode("/",ROOTPATH.$file);
										$mediaFileCompletePath = str_replace($xmlFileName[count($xmlFileName)-1],$sourceUrl,ROOTPATH.$file);
										$mediaPath=$mediaFileCompletePath;
										if(!file_exists($mediaPath))
										{
											$flag = true;
										}
										else
										{
											$verifySignature = md5_file($mediaPath);
											if($digitalSignature == $verifySignature)
											{
												$flag = true;
					
											}
											else
											{
												$flag = false;
												return "Invalid signature-- (".$mediaPath.")\n";
											}
										}
									}
								}
							}
						}
					}
					if($flag == false)
					{
						$flag = 0;
					}else{
						$flag = 1;
					}
					return $flag;
				}
	if($actionType == "DELETE")
	{
		$flag = true;
		if($flag == false)
		{
			$flag = 0;
		}else
		{
			$flag = 1;
		}
		return $flag;
	
	}
	
	//echo $actionType."== UPDATE".$productType. "==";
	return $xml."--Not returning anything".$actionType."== UPDATE".$productType. "==";
}
//To update the Sales Date for each record in PhysicalProduct table
function updateSalesDate($ProdID)
{
		$sql = "SELECT PRODUCT_OFFER.ProdID, Availability.AvailabilityStatus,SALES_TERRITORY.SALES_START_DATE \n"
				  . "FROM Availability INNER JOIN PRODUCT_OFFER ON Availability.ProdID = PRODUCT_OFFER.ProdID \n"
				  . " INNER JOIN SALES_TERRITORY ON SALES_TERRITORY.PRODUCT_OFFER_ID = PRODUCT_OFFER.PRODUCT_OFFER_ID \n"
				  . "WHERE Availability.AvailabilityType = 'PERMANENT' AND \n"
				  . " SALES_TERRITORY.PRICE_CATEGORY = 'PERMANENT' AND \n"
				  . " Availability.AvailabilityStatus = 'I' AND \n"
				  . " PRODUCT_OFFER.ProdID = $ProdID";	           
		  $result = mysql_query($sql);		
		  if(mysql_num_rows($result) > 0)
		  {
			$updateSql = "UPDATE `PhysicalProduct` SET `DownloadStatus` = '1' WHERE `PhysicalProduct`.`ProdID` =".$ProdID;
			$updateResult = mysql_query($updateSql);	
			while ($resultArr = mysql_fetch_assoc($result))
			{			 
			  $updateSql = "UPDATE `PhysicalProduct` SET `SalesDate` = '".$resultArr['SALES_START_DATE']."' WHERE `PhysicalProduct`.`ProdID` =".$ProdID;		 
			  $updateResult = mysql_query($updateSql);	
			  break;										
			}
		  }		 
	
}

function deleteSalesDate($ProdID)
{
	$updateSql = "UPDATE `PhysicalProduct` SET `SalesDate` = 'Null', `DownloadStatus` = '0' WHERE `PhysicalProduct`.`ProdID` =".$ProdID;	
	$updateResult = mysql_query($updateSql);
}	

function sendReportFile($src,$dst,$logFileWrite)
{
	if(!($con = ssh2_connect(REPORTS_SFTP_HOST,REPORTS_SFTP_PORT)))
	{
		echo "Not Able to Establish Connection\n";
		return false;
	}
	else
	{
		if(!ssh2_auth_password($con,REPORTS_SFTP_USER,REPORTS_SFTP_PASS))
		{
			echo "fail: unable to authenticate\n";
			return false;
		}
		else
		{
			$sftp = ssh2_sftp($con);
			if(!is_dir("ssh2.sftp://$sftp".REPORTS_SFTP_PATH."sony_reports/"))
			{
				ssh2_sftp_mkdir($sftp,REPORTS_SFTP_PATH."sony_reports/");
			}

			if(!ssh2_scp_send($con, $src, REPORTS_SFTP_PATH."sony_reports/".$dst, 0644)){
				echo "error sending report to Sony server\n";
				fwrite($logFileWrite, "error sending report to Sony server\n");
				return false;
			}
			else
			{
				echo "Report Sucessfully sent\n";
				fwrite($logFileWrite, "Report Sucessfully sent\n");
				return true;
			}
		}
	}
}

?>