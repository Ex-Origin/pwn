drop table if exists `user`;
create table `user`(
    `uid` int primary key auto_increment,
    `email` varchar(100) unique not null,
    `nickname` varchar(20) unique not null,
    `password` char(64) not null
)charset=utf8;


drop table if exists `challenge`;
create table `challenge`(
    `cid` int primary key auto_increment,
    `name` varchar(100) not null,
    `content` Text,
    `file` varchar(1000) not null,
    `flag` varchar(1000) not null
)charset=utf8;

drop table if exists `solved`;
create table `solved`(
    `sid` int primary key auto_increment,
    `uid` int,
    `cid` int,
    `time` char(20) not null,

    foreign key(`uid`) REFERENCES `user`(`uid`),
    foreign key(`cid`) REFERENCES `challenge`(`cid`)
)charset=utf8;