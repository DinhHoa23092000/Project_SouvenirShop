drop database if exists souvernir;
create database souvernir;
use souvernir;

create table Product(
id int primary key AUTO_INCREMENT,
name varchar(50),
image varchar(50),
price float,
type varchar(50),
detail varchar(255)
);
INSERT INTO `Product`(`name`, `image`, `price`, `type`, `detail`) VALUES ("HỘP BÚT GỖ ĐỒNG HỒ","Souvernir/Product/QuaDeBan/hopbutgodongho.jpg",105000 , "QUÀ ĐỂ BÀN","Mua Hộp bút gỗ với giá tốt tại Quà Tặng Hoàn Hảo. Mua sắm trực tuyến ngay bây giờ tại Quà Tặng Hoàn Hảo để nhận ngay ưu đãi của các sản phẩm từ thương hiệu và một số sản phẩm Hộp bút gỗ");
INSERT INTO `Product`(`name`, `image`, `price`, `type`, `detail`) VALUES ("BÓ HOA GẤU BÔNG","Souvernir/Product/GauBong/bohoagaubong.jpg",230000 , "GẤU BÔNG","Mua Bó hoa 11 gấu bông mặc váy xanh luxury với giá tốt tại Quà Tặng Hoàn Hảo. Mua sắm trực tuyến ngay bây giờ tại Quà Tặng Hoàn Hảo để nhận ngay ưu đãi.");
INSERT INTO `Product`(`name`, `image`, `price`, `type`, `detail`) VALUES ("LỌ PHUN SƯƠNG TỰ ĐỘNG","Souvernir/Product/TienIch/lophunsuongtudong.jpg",265000 , "TIỆN ÍCH","Mua Lọ hoa phun sương tự động với giá tốt tại Quà Tặng Hoàn Hảo. Mua sắm trực tuyến ngay bây giờ tại Quà Tặng Hoàn Hảo để nhận ngay ưu đãi.");
INSERT INTO `Product`(`name`, `image`, `price`, `type`, `detail`) VALUES ("MÓC MÈO THẦN TÀI","Souvernir/Product/MocKhoa/mocmeothantai.jpg",105000 , "MÓC KHÓA","Từ lâu móc khóa đã là vật dụng khá phổ biến và thân thiết với giới trẻ. Bên cạnh những chức năng căn bản, thông qua móc khóa nói lên rất nhiều điều về người sở hữu nó về sở thích, cá tính");
INSERT INTO `Product`(`name`, `image`, `price`, `type`, `detail`)VALUES ("HEO BÔNG MẶC ÁO THIÊN THẦN","Souvernir/Product/GauBong/1.jpg",230000 , "GẤU BÔNG","Mua Bó hoa 11 gấu bông mặc váy xanh luxury với giá tốt tại Quà Tặng Hoàn Hảo. Mua sắm trực tuyến ngay bây giờ tại Quà Tặng Hoàn Hảo để nhận ngay ưu đãi.");
INSERT INTO `Product`(`name`, `image`, `price`, `type`, `detail`) VALUES ("VÍ ĐỰNG NAMECARD DA CAO CẤP","Souvernir/Product/QuaDeBan/vinamecard.jpg",340000 , "QUÀ ĐỂ BÀN","In logo lên bề mặt hộp đựng namecard là cách quảng bá hiệu quả và lâu dài nhất cho thương hiệu của bạn. Sản phẩm được sử dụng rộng rãi thông qua việc doanh nghiệp làm quà tặng, tạo ra hiệu ứng");
INSERT INTO `Product`(`name`, `image`, `price`, `type`, `detail`) VALUES ("GƯƠNG TRANG ĐIỂM","Souvernir/Product/TienIch/guongtrangdiem.jpg",180000 , "TIỆN ÍCH","Vẻ ngoài không chỉ là yếu tố đầu tiên gây thiện cảm với người xung quanh mà nó còn thể hiện được tính cách và lối sống của mỗi người. Với tác động của môi trường, khiến đầu tóc bị rối");
INSERT INTO `Product`(`name`, `image`, `price`, `type`, `detail`) VALUES ("ĐỒNG HỒ CÁT HỘP BÚT","Souvernir/Product/QuaDeBan/donghocathopbut.jpg",600000 , "QUÀ ĐỂ BÀN","Sản phẩm đồng hồ cát hộp bút kim loại phong cách cổ điển này cũng là 1 trong những món đồ tiện tích, tích hợp tính năm cắm bút để trên bàn học, bàn làm việc….");
INSERT INTO `Product`(`name`, `image`, `price`, `type`, `detail`) VALUES ("ĐỒNG HỒ CÁT VINTAGE","Souvernir/Product/QuaDeBan/donghocatvintage.jpg",550000 , "QUÀ ĐỂ BÀN","Đồng hồ cát vintage cổ điển xoay 360 độ với thời gian cát chảy 15 phút sẽ là món đồ trang trí cho căn phòng của bạn thêm ấn tượng nổi bật với một phong cách Châu Âu bán cổ điển, hiện đại.");
INSERT INTO `Product`(`name`, `image`, `price`, `type`, `detail`) VALUES ("CHUÔNG GIÓ CẦU THỦY TINH TOTORO","Souvernir/Product/QuaTangPhaLe/chuonggio.jpg",120000 , "QUÀ TẶNG PHA LÊ","CHUÔNG GIÓ TRANG TRÍ NHẬT BẢN VINTAGE điều ước lọ thủy tinh Cực kỳ dễ thương, sắc nét bạn có thể làm quà tặng hoặc trang trí góc phòng với những chiếc chuông gió xinh xắn, trang trí theo phong cách vintage");
INSERT INTO `Product`(`name`, `image`, `price`, `type`, `detail`) VALUES ("BÚT KÍ KIM LOẠI CAO CẤP","Souvernir/Product/But/butkikimloaicaocap.jpg",250000 , "BÚT","Bút kim loại  là từ khi sự phát triển công nghệ chưa bùng nổ. Việc sử dụng một chiếc bút kim loại cao cấp và chất lượng vẫn luôn thể hiện được hình ảnh đặc biệt của  con người trong công việc.");

create table User(
id int primary key AUTO_INCREMENT,
fullName varchar(50),
username varchar(50) UNIQUE,
password varchar(50),
role varchar(20)
);
INSERT INTO `user`(`username`, `fullName` ,`password`,`role`) VALUES ('admin', 'Administator' ,'admin', 'admin');
INSERT INTO `user`(`username`, `fullName` ,`password`,`role`) VALUES ('mai', 'Ho Thi Mai', '123', 'user');

CREATE TABLE cart(
    id int not null AUTO_INCREMENT PRIMARY KEY,
    idPr int,
    pic varchar(255),
    name varchar(20),
    price double,
    quantity int,
    total double,
    idUser int,
    FOREIGN KEY (idUser) REFERENCES User(id));
    
create table customer(
id int auto_increment primary key,
name varchar(255),
address varchar(255),
phone varchar(20),
id_account int,
FOREIGN KEY (id_account) REFERENCES User(id));   

create table orders(
id int auto_increment primary key,
date_order date,
id_cus int,
total_price decimal(10,0),
FOREIGN KEY (id_cus) REFERENCES customer(id));

create table order_detail(
id_ord int not null,
id_pro int not null,
quantity int,
primary key(id_ord, id_pro),
FOREIGN KEY (id_ord) REFERENCES orders(id),
FOREIGN KEY (id_pro) REFERENCES Product(id)
);
create table chatbox(
id int not null auto_increment primary key,
text varchar(255),
timeInbox time,
idUser int,
idChat int,
FOREIGN KEY (idUser) REFERENCES user(id));

    

select *from User;
select *from Product;
select *from cart;
select *from customer;
select *from orders;
select *from order_detail;

delete from cart where idUser = 2;

select orders.id, c.name, c.address, od.id_pro, od.quantity, orders.total_price, orders.date_order
from customer as c, orders, order_detail as od, product as p
where orders.id=od.id_ord
and od.id_pro=p.id
and orders.id_cus=c.id
and orders.id=2;

select p.name, p.price, od.quantity
from Product as p, orders as o, order_detail as od, customer as c
where o.id=od.id_ord
and od.id_pro=p.id
and o.id_cus=c.id
and o.id=2;

select orders.id, orders.date_order, customer.name, orders.total_price
from orders, customer
where orders.id_cus=customer.id;

select p.name, p.price, od.quantity
from Product as p, orders as o, order_detail as od, customer as c, user as u
where o.id=od.id_ord
and od.id_pro=p.id
and c.id_account=u.id
and u.username="mai";



select *from chatbox;

select *from chatbox where idUser in (1,2) and idChat in (1,2) ORDER BY timeInbox ASC;

select *from chatbox where idUser in (1,3) and idChat in (1,3) ORDER BY timeInbox ASC;



