ALTER TABLE `library_purchases` DROP INDEX `purchased_order_num` 
ALTER TABLE `library_purchases` ADD UNIQUE (`library_id` ,`purchased_order_num` );