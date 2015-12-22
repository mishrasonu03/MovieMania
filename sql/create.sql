/**** Movie Table *****/
-- Every movie must have a unique ID (Primary Key Constraint)
-- Every movie must have a title
-- Every movie must have a MPAA rating
-- Every movie must have a positive ID (Check Constraint)
-- Every movie must have release year from 1890 (first movie was released around this) to current year (Check Constraint)

CREATE TABLE Movie(
			id INT NOT NULL,
			title VARCHAR(100) NOT NULL, 
			year INT NOT NULL, 
			rating VARCHAR(10) NOT NULL, 
			company VARCHAR(50),
			PRIMARY KEY (id),
			CHECK (id > 0),
			CHECK (year >=1890 AND year <= YEAR(CURDATE()))
			) ENGINE=INNODB;

/***** Actor Table *****/
-- Every actor must have a unique ID (Primary Key Constraint)
-- Every actor must have a date of birth
-- Every actor must have a first name
-- Every actor must have a positive ID (Check Constraint)
-- Every actor must have dob no later than today (Check Constraint)
-- Every actor must have date of death, if not missing, after dob and no later than today (Check Constraint)

CREATE TABLE Actor(
			id INT NOT NULL,
			last VARCHAR(20), 
			first VARCHAR(20) NOT NULL, 
			sex VARCHAR(6), 
			dob DATE NOT NULL, 
			dod DATE,
			PRIMARY KEY (id),
			CHECK (id > 0),
			CHECK (dob <= CURDATE()),
			CHECK ((dod <= CURDATE() AND dod >= dob) OR dod IS NULL)
			) ENGINE=INNODB;

/***** Director Table *****/
-- Every director must have a unique ID (Primary Key Constraint)
-- Every director must have a date of birth
-- Every director must have a first name
-- Every director must have a positive ID (Check Constraint)
-- Every director must have dob no later than today (Check Constraint)
-- Every director must have date of death, if not missing, after dob and no later than today (Check Constraint)

CREATE TABLE Director(
			id INT NOT NULL, 
			last VARCHAR(20), 
			first VARCHAR(20) NOT NULL, 
			dob DATE NOT NULL, 
			dod DATE,
			PRIMARY KEY (id),
			CHECK (id > 0),
			CHECK (dob <= CURDATE()),
			CHECK ((dod <= CURDATE() AND dod >= dob) OR dod IS NULL)
			) ENGINE=INNODB;

/***** MovieGenre Table *****/
-- Every movie id must be present in Movie table (Foreign Key Constraint)

CREATE TABLE MovieGenre(
			mid INT, 
			genre VARCHAR(20),
			FOREIGN KEY (mid) REFERENCES Movie(id)
			) ENGINE=INNODB;

/***** MovieDirector Table *****/
-- Every movie id must be present in Movie table (Foreign Key Constraint)
-- Every director id must be present in Director table (Foreign Key Constraint)

CREATE TABLE MovieDirector(
			mid INT,
			did INT,
			FOREIGN KEY (did) REFERENCES Director(id),
			FOREIGN KEY (mid) REFERENCES Movie(id)
			) ENGINE=INNODB;

/***** MovieActor Table *****/
-- Every movie id must be present in Movie table (Foreign Key Constraint)
-- Every actor id must be present in Actor table (Foreign Key Constraint)
-- Every (movie,actor) pair must be unique  (Primary Key Constraint)
-- Every (movie,actor) pair must have a valid role

CREATE TABLE MovieActor(
			mid INT, 
			aid INT, 
			role VARCHAR(50) NOT NULL,
			PRIMARY KEY (mid, aid), 
			FOREIGN KEY (aid) REFERENCES Actor(id),
			FOREIGN KEY (mid) REFERENCES Movie(id)
			) ENGINE=INNODB;

/***** Review Table *****/
-- Every reviewer must have a name
-- Every movie ID must be present in Movie table (Foreign Key Constraint)
-- Every rating should be between 1-5 (Check Constraint)

CREATE TABLE Review(
			name VARCHAR(20) NOT NULL, 
			time TIMESTAMP NOT NULL, 
			mid INT, 
			rating INT, 
			comment VARCHAR(500),
			FOREIGN KEY(mid) REFERENCES Movie(id),
			CHECK (rating >=1 AND rating <=5)
			) ENGINE=INNODB;

/***** MaxPersonID *****/
-- Person id must be present in Actor/Director table (I have not enforced this)

CREATE TABLE MaxPersonID(
			id INT
			) ENGINE=INNODB;

/***** MaxMovieID *****/
-- Movie id must be present in Movie table (I have not enforced this)

CREATE TABLE MaxMovieID(
			id INT
			) ENGINE=INNODB;

