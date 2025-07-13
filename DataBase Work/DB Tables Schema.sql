 DROP TABLE Ledger;  
 DROP TABLE Vendor;  
DROP TABLE LedgerType; 
 DROP TABLE Product;

 DROP TABLE users; 
 DROP TABLE admin;
 DROP TABLE deleteduser;
 DROP TABLE feedback;
 DROP TABLE notification;
 DROP TABLE DealHistory;
 DROP TABLE Deal;
  DROP TABLE Team;
 DROP TABLE Shark;
 DROP TABLE Class;
 DROP TABLE SchoolYear;
 DROP TABLE Setting;
/* DROP TABLE INCOME; */

CREATE TABLE Setting (
SettingID int(16) auto_increment,
Value int(16),
Name varchar(255),
CreatedBy varchar (255),
CreatedDate DateTime,
UpdatedBy varchar(255),
UpdatedDate DateTime,
PRIMARY KEY (SettingID)
);

CREATE TABLE SchoolYear (
SchoolYearID int(16) auto_increment,
YearName varchar(255),
StartDate DateTime,
EndDate DateTime,
Status int,
CreatedBy varchar (255),
CreatedDate DateTime,
UpdatedBy varchar(255),
UpdatedDate DateTime,
PRIMARY KEY (SchoolYearID)
);
SELECT * FROM schoolyear

CREATE TABLE Class (
ClassID int(16) auto_increment,
SchoolYearID int,
ClassName varchar(255),
Room varchar(255),
Status int,
CreatedBy varchar (255),
CreatedDate DateTime,
UpdatedBy varchar(255),
UpdatedDate DateTime,
FOREIGN KEY (SchoolYearID) REFERENCES SchoolYear(SchoolYearID),
PRIMARY KEY (ClassID)
);

CREATE TABLE Shark (
    SharkID INT(16) AUTO_INCREMENT,
    SharkName VARCHAR(255),
    Status INT,
    CreatedBy VARCHAR(255),
    CreatedDate DATETIME,
    UpdatedBy VARCHAR(255),
    UpdatedDate DATETIME,
    PRIMARY KEY (SharkID)
);
/* NOT DONE IN PROD YET
ALTER TABLE Shark DROP COLUMN FirstName;
ALTER TABLE Shark DROP COLUMN LastName;
ALTER TABLE Shark ADD SharkName   varchar(255);
*/


CREATE TABLE Team (
TeamID int(16) auto_increment,
TeamName varchar(255),
ClassID int,
SchoolYearID int,
SharkID int,
IGFollowers int,
Status int,
credit decimal(15,2),
debit decimal(15,2),
balance decimal(15,2),
CreatedBy varchar (255),
CreatedDate DateTime,
UpdatedBy varchar(255),
UpdatedDate DateTime,
FOREIGN KEY (ClassID) REFERENCES Class(ClassID),
FOREIGN KEY (SchoolYearID) REFERENCES SchoolYear(SchoolYearID),
/* FOREIGN KEY (ProductID) REFERENCES Product(ProductID), */
FOREIGN KEY (SharkID) REFERENCES Shark(SharkID),
PRIMARY KEY (TeamID)
);
SELECT * FROM Team
/* DROP TABLE users */

CREATE TABLE Deal (
DealID int(16) auto_increment,
DealName varchar(255),
SharkID int(16),
TeamID int(16),
ClassID int(16),
TotalInvested decimal(15,2),
PercentOwned decimal(15,2),
Status int,
CreatedBy varchar (255),
CreatedDate DateTime,
UpdatedBy varchar(255),
UpdatedDate DateTime,
FOREIGN KEY (SharkID) REFERENCES Shark(SharkID),
FOREIGN KEY (TeamID) REFERENCES Team(TeamID),
FOREIGN KEY (ClassID) REFERENCES Class(ClassID),
PRIMARY KEY (DealID)
);

ALTER TABLE Deal DROP COLUMN totalInvested;
ALTER TABLE Deal ADD TotalInvested   decimal(15,2);

CREATE TABLE DealHistory (
DealHistoryID  int(16) auto_increment,
DealID int(16),
DealName varchar(255),
SharkID int(16),
TeamID int(16),
ClassID int(16),
TotalInvested decimal(15,2),
PercentOwned decimal(15,2),
Status int,
CreatedBy varchar (255),
CreatedDate DateTime,
UpdatedBy varchar(255),
UpdatedDate DateTime,
FOREIGN KEY (DealID) REFERENCES Deal(DealID),
foreign key (SharkID) REFERENCES Shark(SharkID),
FOREIGN KEY (TeamID) REFERENCES Team(TeamID),
FOREIGN KEY (ClassID) REFERENCES Class(ClassID),
PRIMARY KEY (DealHistoryID)
);

IGFollowers int
ALTER TABLE Team ADD IGFollowers int;

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `TeamID` int ,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `gender` varchar(50) NOT NULL,
  `mobile` varchar(50) NOT NULL,
  `designation` varchar(50) NOT NULL,
  `image` varchar(50) NOT NULL,
  `status` int(10) NOT NULL
  );
  ALTER TABLE users ADD FOREIGN KEY (TeamID) references Team(TeamID);
 ALTER TABLE `users`  ADD PRIMARY KEY (`id`);
ALTER TABLE `users`  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
ALTER TABLE `admin`  ADD PRIMARY KEY (`id`);
ALTER TABLE `admin`  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

INSERT INTO `admin` (`id`, `username`, `email`, `password`) VALUES
(1, 'admin', 'admin@admin.com', '9ae2be73b58b565bce3e47493a56e26a');

CREATE TABLE `deleteduser` (
  `id` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `deltime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
ALTER TABLE `deleteduser`  ADD PRIMARY KEY (`id`);
ALTER TABLE `deleteduser`  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `sender` varchar(50) NOT NULL,
  `reciver` varchar(50) NOT NULL,
  `title` varchar(100) NOT NULL,
  `feedbackdata` varchar(500) NOT NULL,
  `attachment` varchar(50) NOT NULL,
  `classid` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
ALTER TABLE `feedback`  ADD PRIMARY KEY (`id`);
ALTER TABLE `feedback`  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

/*
ALTER TABLE feedback ADD classid int;
ALTER TABLE feedback DROP COLUMN teamid;
*/

CREATE TABLE `notification` (
  `id` int(11) NOT NULL,
  `notiuser` varchar(50) NOT NULL,
  `notireciver` varchar(50) NOT NULL,
  `notitype` varchar(50) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `classid` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
ALTER TABLE `notification`  ADD PRIMARY KEY (`id`);
ALTER TABLE `notification`  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

/*
ALTER TABLE notification ADD classid int;
ALTER TABLE notification DROP COLUMN teamid;
*/
CREATE TABLE Product (
    ProductID int(16) auto_increment,   --
    ProductName varchar(255),           --
    Description varchar(255),           --
    RetailPrice decimal(15,2),          --
    WholeSalePrice decimal(15,2),       --
    QtySoldRetail  int,                       --
    QtySoldWholesale int,
    NumberofPotentialCustomers int,     --
    InputCost    decimal(15,2),         --
    image varchar(50) NOT NULL,         --
    TeamID int,                         --
    StudentID int,						--
    Status int,                         --
	CreatedBy varchar (255),            --
	CreatedDate DateTime,            --
	UpdatedBy varchar(255),            --
	UpdatedDate DateTime,            --
    FOREIGN KEY (TeamID) REFERENCES Team(TeamID),
     FOREIGN KEY (StudentID) REFERENCES users(id),
    PRIMARY KEY (ProductID)
);
SELECT * FROM Product;
/*
ALTER TABLE Product ADD image varchar(50) 
*/
/*
ALTER TABLE Product ADD QtySoldWholesale  int;
ALTER TABLE Product ADD TotalSales int;
ALTER TABLE Product ADD InputCost   decimal(15,2);
ALTER TABLE Product ADD ProductCost   decimal(15,2);
ALTER TABLE Product DROP COLUMN QtySold;

ALTER TABLE Product
ADD image varchar(50) ; 
*/

CREATE TABLE LedgerType (
LedgerTypeID int(16) auto_increment,
Description varchar(255),
AmountDefault decimal(15,2),
Debit boolean,
Note varchar (255),
AdminOnly int,
Status int,
CreatedBy varchar (255),
CreatedDate DateTime,
UpdatedBy varchar(255),
UpdatedDate DateTime,
 PRIMARY KEY (LedgerTypeID)
);
SELECT * FROM LedgerType
/*
ALTER TABLE LedgerType
ADD AdminOnly int ; 
*/

select * from LedgerType

/* Not sure if this is needed anymore */
CREATE TABLE Vendor (
	VendorID int(16) auto_increment,
    Name varchar(255),
    Address varchar(255),
    Status int,
    CreatedBy varchar (255),
	CreatedDate DateTime,
	UpdatedBy varchar(255),
	UpdatedDate DateTime,
    PRIMARY KEY (VendorID)
);

 
 /* Debit Positive
   credit Negative (100.00) 
   DROP TABLE Ledger
   */
CREATE TABLE Ledger ( 
    LedgerID int(16) auto_increment,
    LedgerTypeID int,
    TeamID int,
    StudentID int,
    SchoolYearID int,
    VendorID int NULL,
    Amount decimal(15,2),
    Comment varchar(255),
    IPAddress varchar(255),
    Status int,
    DateEntered DateTime,
    
	CreatedBy varchar (255),
	CreatedDate DateTime,
	UpdatedBy varchar(255),
	UpdatedDate DateTime,
    FOREIGN KEY (TeamID) REFERENCES Team(TeamID),
    FOREIGN KEY (StudentID) REFERENCES users(id),
    FOREIGN KEY (SchoolYearID) REFERENCES SchoolYear(SchoolYearID),
    FOREIGN KEY (VendorID) REFERENCES Vendor(VendorID),

    FOREIGN KEY (LedgerTypeID) REFERENCES LedgerType(LedgerTypeID),
    PRIMARY KEY (LedgerID)
);

CREATE USER 'web'@'localhost' IDENTIFIED BY 'password';  /*THIS CAN't be done in phpMyAdmin*/

-- Grant privileges to the new user
GRANT ALL PRIVILEGES ON *.* TO 'web'@'localhost';

-- Refresh privileges
FLUSH PRIVILEGES;

INSERT INTO Setting (Value,Name,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate) VALUES (1,'CurrentClassID' ,'Admin',now(3),'Admin',now(3));
INSERT INTO Setting (Value,Name,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate) VALUES (1,'CurrentSchoolYearID' ,'Admin',now(3),'Admin',now(3));

SELECT * FROM Setting


INSERT INTO SchoolYear (YearName,StartDate,EndDate,Status,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate) VALUES ('School Year 2024-2025','2024-8-6','2025-5-29',1 ,'Admin',now(3),'Admin',now(3));
INSERT INTO SchoolYear (YearName,StartDate,EndDate,Status,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate) VALUES ('School Year 2025-2026','2025-8-6','2026-5-29',1 ,'Admin',now(3),'Admin',now(3));
INSERT INTO SchoolYear (YearName,StartDate,EndDate,Status,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate) VALUES ('School Year 2026-2027','2026-8-6','2027-5-29',1 ,'Admin',now(3),'Admin',now(3));
INSERT INTO SchoolYear (YearName,StartDate,EndDate,Status,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate) VALUES ('School Year 2027-2028','2027-8-6','2028-5-29',1 ,'Admin',now(3),'Admin',now(3));
INSERT INTO SchoolYear (YearName,StartDate,EndDate,Status,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate) VALUES ('School Year 2028-2029','2028-8-6','2029-5-29',1 ,'Admin',now(3),'Admin',now(3));
INSERT INTO SchoolYear (YearName,StartDate,EndDate,Status,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate) VALUES ('School Year 2029-2030','2029-8-6','2030-5-29',1 ,'Admin',now(3),'Admin',now(3));
INSERT INTO SchoolYear (YearName,StartDate,EndDate,Status,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate) VALUES ('School Year 2030-2031','2030-8-6','2031-5-29',1 ,'Admin',now(3),'Admin',now(3));
INSERT INTO SchoolYear (YearName,StartDate,EndDate,Status,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate) VALUES ('School Year 2031-2032','2031-8-6','2032-5-29',1 ,'Admin',now(3),'Admin',now(3));
INSERT INTO SchoolYear (YearName,StartDate,EndDate,Status,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate) VALUES ('School Year 2032-2033','2032-8-6','2033-5-29',1 ,'Admin',now(3),'Admin',now(3));
INSERT INTO SchoolYear (YearName,StartDate,EndDate,Status,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate) VALUES ('School Year 2033-2034','2033-8-6','2034-5-29',1 ,'Admin',now(3),'Admin',now(3));
INSERT INTO SchoolYear (YearName,StartDate,EndDate,Status,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate) VALUES ('School Year 2034-2035','2034-8-6','2035-5-29',1 ,'Admin',now(3),'Admin',now(3));
INSERT INTO SchoolYear (YearName,StartDate,EndDate,Status,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate) VALUES ('School Year 2035-2036','2035-8-6','2036-5-29',1 ,'Admin',now(3),'Admin',now(3));
select * FROM SchoolYear;

INSERT INTO Class (SchoolYearID,ClassName,Room,Status,CreatedBy,CreatedDate, UpdatedBy,UpdatedDate) VALUES (1,'Engineering Class 2024-2025','P203',1 ,'Admin',now(3),'Admin',now(3));
INSERT INTO Class (SchoolYearID,ClassName,Room,Status,CreatedBy,CreatedDate, UpdatedBy,UpdatedDate) VALUES (2,'Engineering Class 2025-2026','P203',1 ,'Admin',now(3),'Admin',now(3));
 SELECT * FROM Class

INSERT INTO Shark (FirstName, LastName,Status,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate) VALUES ('Mark', 'Cuban',1,'Admin',now(3),'Admin',now(3));
INSERT INTO Shark (FirstName, LastName,Status,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate) VALUES ('Barbara', 'Corcoran',1,'Admin',now(3),'Admin',now(3));
INSERT INTO Shark (FirstName, LastName,Status,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate) VALUES ('Lori', 'Greiner',1,'Admin',now(3),'Admin',now(3));
INSERT INTO Shark (FirstName, LastName,Status,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate) VALUES ('Robert', 'Herjavec',1,'Admin',now(3),'Admin',now(3));
INSERT INTO Shark (FirstName, LastName,Status,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate) VALUES ('Kevin', 'OLeary',1,'Admin',now(3),'Admin',now(3));
INSERT INTO Shark (FirstName, LastName,Status,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate) VALUES ('Daymond', 'John',1,'Admin',now(3),'Admin',now(3));
INSERT INTO Shark (FirstName, LastName,Status,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate) VALUES ('Daniel', 'Lubetzky',1,'Admin',now(3),'Admin',now(3)); 
INSERT INTO Shark (FirstName, LastName,Status,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate) VALUES ('Kevin', 'Harrington',1,'Admin',now(3),'Admin',now(3));

INSERT INTO Team (TeamName,ClassID,schoolyearID,SharkID,IGFollowers,Status,credit,debit,balance,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate) VALUES ('Lincoln Best',           1,1,1,0,1,0.00,0.00,0.00,'Admin',now(3),'Admin',now(3));
INSERT INTO Team (TeamName,ClassID,schoolyearID,SharkID,IGFollowers,Status,credit,debit,balance,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate) VALUES ('Herecles Design',        1,1,2,0,1,0.00,0.00,0.00,'Admin',now(3),'Admin',now(3));
INSERT INTO Team (TeamName,ClassID,schoolyearID,SharkID,IGFollowers,Status,credit,debit,balance,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate) VALUES ('Prestiques Design',      1,1,3,0,1,0.00,0.00,0.00,'Admin',now(3),'Admin',now(3));
INSERT INTO Team (TeamName,ClassID,schoolyearID,SharkID,IGFollowers,Status,credit,debit,balance,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate) VALUES ('Timeless Honors',        1,1,4,0,1,0.00,0.00,0.00,'Admin',now(3),'Admin',now(3));
INSERT INTO Team (TeamName,ClassID,schoolyearID,SharkID,IGFollowers,Status,credit,debit,balance,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate) VALUES ('Western Rodeo Enterpise',1,1,5,0,1,0.00,0.00,0.00,'Admin',now(3),'Admin',now(3));
INSERT INTO Team (TeamName,ClassID,schoolyearID,SharkID,IGFollowers,Status,credit,debit,balance,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate) VALUES ('FS&F',                   1,1,6,0,1,0.00,0.00,0.00,'Admin',now(3),'Admin',now(3));
INSERT INTO Team (TeamName,ClassID,schoolyearID,SharkID,IGFollowers,Status,credit,debit,balance,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate) VALUES ('ORB Enterprise',         1,1,7,0,1,0.00,0.00,0.00,'Admin',now(3),'Admin',now(3));



SELECT * FROM Product



SHOW TABLE STATUS LIKE 'Team';
ALTER TABLE team AUTO_INCREMENT = 7
SELECT * FROM Team

/*INSERT INTO Student (StudentID,TeamID,Grade,ClassID,LastName,FirstName,SchoolYearID,Email,Address, City,Status,credit,debit,balance,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate) VALUES (4234,1,12,1,'Morrisey','Bill',2,'bill.morrisey@gmail.com','4394 Billings Dr','Lincoln',1,0,0,0,'Admin',now(3),'Admin',now(3));*/
/*INSERT INTO Student (StudentID,TeamID,Grade,ClassID,LastName,FirstName,SchoolYearID,Email,Address, City,Status,credit,debit,balance,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate) VALUES (4233,1,12,1,'Boris','Veliskoph',2,'Boris.Veliskoph@gmail.com','495 rainbow st','Lincoln',1,0,0,0,'Admin',now(3),'Admin',now(3));*/
/*INSERT INTO Student (StudentID,TeamID,Grade,ClassID,LastName,FirstName,SchoolYearID,Email,Address, City,Status,credit,debit,balance,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate) VALUES (4211,1,12,1,'Seacrist','Spencer',2,'spencer.seacrist@gmail.com','439 Hedgewood Ave','Rocklin',1,0,0,0,'Admin',now(3),'Admin',now(3));*/

/*INSERT INTO Student (StudentID,TeamID,Grade,ClassID,LastName,FirstName,SchoolYearID,Email,Address, City,Status,credit,debit,balance,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate) VALUES (4239,2,12,1,'Amber','Lauran',2,'@gmail.com','186 Smiths Ave','Lincoln',1,0,0,0,'Admin',now(3),'Admin',now(3));*/
/*INSERT INTO Student (StudentID,TeamID,Grade,ClassID,LastName,FirstName,SchoolYearID,Email,Address, City,Status,credit,debit,balance,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate) VALUES (4235,2,12,1,'Doe','Frank',2,'@gmail.com','419 Burket Ave','Lincoln',1,0,0,0,'Admin',now(3),'Admin',now(3));*/
/*INSERT INTO Student (StudentID,TeamID,Grade,ClassID,LastName,FirstName,SchoolYearID,Email,Address, City,Status,credit,debit,balance,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate) VALUES (4236,2,12,1,'Ristor','Holden',2,'@gmail.com','58 Rocket Ave','Lincoln',1,0,0,0,'Admin',now(3),'Admin',now(3));*/

/*INSERT INTO Student (StudentID,TeamID,Grade,ClassID,LastName,FirstName,SchoolYearID,Email,Address, City,Status,credit,debit,balance,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate) VALUES (4237,3,12,1,'Smith','Mike',2,'@gmail.com','74 Burgness Ave','Lincoln',1,0,0,0,'Admin',now(3),'Admin',now(3));*/
/*INSERT INTO Student (StudentID,TeamID,Grade,ClassID,LastName,FirstName,SchoolYearID,Email,Address, City,Status,credit,debit,balance,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate) VALUES (4238,3,12,1,'Johnson','Beatrice',2,'@gmail.com','483 Roaster DR','Lincoln',1,0,0,0,'Admin',now(3),'Admin',now(3));*/
/*INSERT INTO Student (StudentID,TeamID,Grade,ClassID,LastName,FirstName,SchoolYearID,Email,Address, City,Status,credit,debit,balance,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate) VALUES (4240,3,12,1,'Roueiz','Wyatt',2,'@gmail.com','43 Forward DR','Lincoln',1,0,0,0,'Admin',now(3),'Admin',now(3));*/

/*INSERT INTO Student (StudentID,TeamID,Grade,ClassID,LastName,FirstName,SchoolYearID,Email,Address, City,Status,credit,debit,balance,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate) VALUES (4241,3,11,1,'Finster','Paul',2,'@gmail.com','32 Garden Ave','Lincoln',1,0,0,0,'Admin',now(3),'Admin',now(3));*/
/*INSERT INTO Student (StudentID,TeamID,Grade,ClassID,LastName,FirstName,SchoolYearID,Email,Address, City,Status,credit,debit,balance,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate) VALUES (4243,3,11,1,'Park','Kelly',2,'@gmail.com','4830 East Ave','Lincoln',1,0,0,0,'Admin',now(3),'Admin',now(3));*/
/*INSERT INTO Student (StudentID,TeamID,Grade,ClassID,LastName,FirstName,SchoolYearID,Email,Address, City,Status,credit,debit,balance,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate) VALUES (4245,3,11,1,'Roaster','Billy',2,'Billy.Roaster@gmail.com','4311 Forward DR','Lincoln',1,0,0,0,'Admin',now(3),'Admin',now(3));*/

/*INSERT INTO Student (StudentID,TeamID,Grade,ClassID,LastName,FirstName,SchoolYearID,Email,Address, City,Status,credit,debit,balance,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate) VALUES (4247,3,11,1,'Velenski','Boris',2,'Boris.Velenski@gmail.com','34 Burgness Ave','Lincoln',1,0,0,0,'Admin',now(3),'Admin',now(3));*/
/*INSERT INTO Student (StudentID,TeamID,Grade,ClassID,LastName,FirstName,SchoolYearID,Email,Address, City,Status,credit,debit,balance,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate) VALUES (4249,3,11,1,'Aarset','Nataile',2,'Natalie.Aarset@gmail.com','43 Minnie DR','Lincoln',1,0,0,0,'Admin',now(3),'Admin',now(3));*/
/*INSERT INTO Student (StudentID,TeamID,Grade,ClassID,LastName,FirstName,SchoolYearID,Email,Address, City,Status,credit,debit,balance,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate) VALUES (4250,3,11,1,'Vega','Mike',2,'Mike.Vega@gmail.com','41 Franklin DR','Lincoln',1,0,0,0,'Admin',now(3),'Admin',now(3));*/

/*INSERT INTO Student (StudentID,TeamID,Grade,ClassID,LastName,FirstName,SchoolYearID,Email,Address, City,Status,credit,debit,balance,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate) VALUES (4251,3,11,1,'Smith','Frank',2,'Frank.Smith@gmail.com','7224 Fell Ave','Lincoln',1,0,0,0,'Admin',now(3),'Admin',now(3));*/
/*INSERT INTO Student (StudentID,TeamID,Grade,ClassID,LastName,FirstName,SchoolYearID,Email,Address, City,Status,credit,debit,balance,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate) VALUES (4254,3,11,1,'Bumbgardner','Wendy',2,'Wendy.bumbgardner@gmail.com','4833 Crankster DR','Lincoln',1,0,0,0,'Admin',now(3),'Admin',now(3));*/
/*INSERT INTO Student (StudentID,TeamID,Grade,ClassID,LastName,FirstName,SchoolYearID,Email,Address, City,Status,credit,debit,balance,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate) VALUES (4255,3,11,1,'Mendez','Ron',2,'ron.mendez@gmail.com','254 Raster DR','Lincoln',1,0,0,0,'Admin',now(3),'Admin',now(3));  */



SELECT * FROM Student

INSERT INTO Vendor (Name, Address,Status,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate) VALUES('Ramsey','4023 FrontLine Rd',1,'Admin',now(3),'Admin',now(3));
INSERT INTO Vendor (Name, Address,Status,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate) VALUES('Grainger','2000 Industrial Way',1,'Admin',now(3),'Admin',now(3));
INSERT INTO Vendor (Name, Address,Status,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate) VALUES('none','N/A',1,'Admin',now(3),'Admin',now(3));

SELECT * FROM Vendor
 /* 
   credit Negative (100.00)
   Debit Positive 100.00 */
   /* CREDIT */
INSERT INTO LedgerType (Description,AmountDefault,Debit,Note,AdminOnly,Status,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate) VALUES ('Utilities',(25.00),0,'',0,1,'Admin',now(3),'Admin',now(3));
INSERT INTO LedgerType (Description,AmountDefault,Debit,Note,AdminOnly,Status,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate) VALUES ('Taxes',25.00,0,'',0,1,'Admin',now(3),'Admin',now(3));
INSERT INTO LedgerType (Description,AmountDefault,Debit,Note,AdminOnly,Status,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate) VALUES ('Rent/Facilities',25.00,0,'',0,1,'Admin',now(3),'Admin',now(3));
INSERT INTO LedgerType (Description,AmountDefault,Debit,Note,AdminOnly,Status,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate) VALUES ('Materials',25.00,0,'',0,1,'Admin',now(3),'Admin',now(3));
INSERT INTO LedgerType (Description,AmountDefault,Debit,Note,AdminOnly,Status,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate) VALUES ('Shop Lab Fee',25.00,0,'',0,1,'Admin',now(3),'Admin',now(3));
INSERT INTO LedgerType (Description,AmountDefault,Debit,Note,AdminOnly,Status,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate) VALUES ('Insurance',25.00,0,'',0,1,'Admin',now(3),'Admin',now(3));
INSERT INTO LedgerType (Description,AmountDefault,Debit,Note,AdminOnly,Status,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate) VALUES ('Marketing',25.00,0,'',0,1,'Admin',now(3),'Admin',now(3));
INSERT INTO LedgerType (Description,AmountDefault,Debit,Note,AdminOnly,Status,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate) VALUES ('Wheel of Doom',25.00,0,'',0,1,'Admin',now(3),'Admin',now(3));
INSERT INTO LedgerType (Description,AmountDefault,Debit,Note,AdminOnly,Status,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate) VALUES ('Other Exp.',25.00,0,'',0,1,'Admin',now(3),'Admin',now(3));
INSERT INTO LedgerType (Description,AmountDefault,Debit,Note,AdminOnly,Status,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate) VALUES ('Salaries in Team',25.00,0,'',0,1,'Admin',now(3),'Admin',now(3));
INSERT INTO LedgerType (Description,AmountDefault,Debit,Note,AdminOnly,Status,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate) VALUES ('Salaries Ext Team',25.00,0,'',0,1,'Admin',now(3),'Admin',now(3));
INSERT INTO LedgerType (Description,AmountDefault,Debit,Note,AdminOnly,Status,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate) VALUES ('Salaries Ext Individual',25.00,0,'',0,1,'Admin',now(3),'Admin',now(3));
INSERT INTO LedgerType (Description,AmountDefault,Debit,Note,AdminOnly,Status,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate) VALUES ('Fines',25.00,0,'',1,1,'Admin',now(3),'Admin',now(3));
INSERT INTO LedgerType (Description,AmountDefault,Debit,Note,AdminOnly,Status,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate) VALUES ('Outsourcing',25.00,0,'',0,1,'Admin',now(3),'Admin',now(3));
/* DEBIT */
INSERT INTO LedgerType (Description,AmountDefault,Debit,Note,AdminOnly,Status,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate) VALUES ('Start Up Capital',25.00,1,'',1,1,'Admin',now(3),'Admin',now(3));
INSERT INTO LedgerType (Description,AmountDefault,Debit,Note,AdminOnly,Status,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate) VALUES ('Investment Wire',25.00,1,'',1,1,'Admin',now(3),'Admin',now(3));
SELECT * FROM LedgerType WHERE Status = 1


UPDATE LedgerType set AdminOnly = 1 where Description = 'Investment Wire'
UPDATE LedgerType set AdminOnly = 0 WHERE LedgerTypeID != 13

SELECT * FROM ledgertype
SELECT * FROM Ledger
SELECT LedgerID,LedgerTypeID,TeamID,StudentID,SchoolYearID,Amount,DateEntered FROM Ledger
SELECT distinct(TeamID), COUNT(*)  FROM Ledger GROUP BY TeamID
select * from test.users
SELECT * FROM test.Team
SELECT * from Team where Status = 1
SELECT TeamID,TeamName from team where Status = 1
SELECT SUM(Amount) FROM Ledger
 
SELECT LT.Description, L.* FROM ledger L, LedgerType LT where L.LedgerTypeID = LT.LedgerTypeID AND L.TeamID = 1 and L.SchoolYearID = 1 ORDER BY LedgerID

SELECT SUM(Amount) FROM Ledger where TeamID = 1 and SchoolYearID = 1 and Amount < 0
-390,631.03
SELECT SUM(Amount) FROM Ledger where TeamID = 1 and SchoolYearID = 1 and Amount > 0
391,050.00

SELECT 391050.00 - 390631.03    // 418.97

SELECT SUM(Amount) FROM Ledger where TeamID = 1 and SchoolYearID = 1   //418.97
