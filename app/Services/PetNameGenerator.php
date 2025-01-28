<?php


namespace App\Services;
class PetNameGenerator {
    private array $maleNames = [
        'Abel', 'Ace', 'Adam', 'Admiral', 'Aj', 'Ajax', 'Aldo', 'Alex', 'Alf', 'Alfie',
        'Amigo', 'Amos', 'Andy', 'Angel', 'Angus', 'Apollo', 'Archie', 'Argus', 'Aries', 'Armanti',
        'Arnie', 'Arrow', 'Astro', 'Atlas', 'Augie', 'Aussie', 'Austin', 'Axel', 'Axle', 'Bacchus',
        'Bam-bam', 'Bandit', 'Banjo', 'Barclay', 'Barker', 'Barkley', 'Barley', 'Barnaby', 'Barney', 'Baron',
        'Bart', 'Basil', 'Baxter', 'Beamer', 'Bear', 'Beau', 'Beaux', 'Ben', 'Benji', 'Benny',
        'Benson', 'Bentley', 'Bernie', 'Biablo', 'Big Boy', 'Biggie', 'Billy', 'Bingo', 'Bishop',
        'Bits', 'Bj', 'Black-Jack', 'Blast', 'Blaze', 'Blue', 'Bo', 'Bob', 'Bobbie', 'Bobby',
        'Bobo', 'Bodie', 'Bogey', 'Bones', 'Bongo', 'Boo', 'Booker', 'Boomer', 'Boone', 'Booster',
        'Boris', 'Bosco', 'Bosley', 'Boss', 'Boy', 'Bozley', 'Bradley', 'Brady', 'Braggs', 'Brando',
        'Bruiser', 'Bruno', 'Brutus', 'Bubba', 'Buck', 'Buckeye', 'Bucko', 'Bucky', 'Bud', 'Budda',
        'Buddie', 'Buddy', 'Buddy Boy', 'Bullet', 'Bullwinkle', 'Bully', 'Bumper', 'Bunky', 'Buster',
        'Buster-brown', 'Butch', 'Butchy', 'Caesar', 'Calvin', 'Capone', 'Captain', 'Chad', 'Chamberlain',
        'Champ', 'Chance', 'Charles', 'Charlie', 'Charlie Brown', 'Charmer', 'Chase', 'Chauncey', 'Chaz',
        'Chester', 'Chevy', 'Chewie', 'Chewy', 'Chico', 'Chief', 'Chip', 'Chipper', 'Chippy', 'Chubbs',
        'Chucky', 'Clancy', 'Clifford', 'Clyde', 'Cole', 'Comet', 'Commando', 'Conan', 'Connor', 'Cooper',
        'Copper', 'Corky', 'Cosmo', 'Cotton', 'Cozmo', 'Crackers', 'Cricket', 'Cubby', 'Cubs', 'Cujo',
        'Curly', 'Curry', 'Cyrus', 'Dallas', 'Dante', 'Darwin', 'Dave', 'Deacon', 'Dewey', 'Dexter',
        'Diego', 'Diesel', 'Digger', 'Dillon', 'Dino', 'Doc', 'Dodger', 'Duke', 'Duncan', 'Dunn',
        'Dusty', 'Dylan', 'Earl', 'Eddie', 'Eddy', 'Edgar', 'Edsel', 'Einstein', 'Elliot', 'Elmo',
        'Elvis', 'Elwood', 'Ernie', 'Felix', 'Ferris', 'Fido', 'Flash', 'Flint', 'Floyd', 'Fonzie',
        'Frankie', 'Franky', 'Fred', 'Freddie', 'Freddy', 'Fritz', 'Frodo', 'Frosty', 'George', 'Georgie',
        'Gibson', 'Gilbert', 'Goober', 'Gordon', 'Grady', 'Griffin', 'Gringo', 'Grover', 'Guido', 'Gunner',
        'Gus', 'Guy', 'Hammer', 'Hank', 'Hans', 'Hardy', 'Harley', 'Harpo', 'Harrison', 'Harry',
        'Harvey', 'Henry', 'Hercules', 'Higgins', 'Hobbes', 'Homer', 'Hooch', 'Hoover', 'Houdini', 'Howie',
        'Hudson', 'Huey', 'Hugh', 'Hugo', 'Humphrey', 'Hunter', 'Jack', 'Jackpot', 'Jackson', 'Jagger',
        'Jake', 'Jasper', 'Jaxson', 'Jerry', 'Jesse', 'Jesse James', 'Jester', 'Jet', 'Jethro', 'Jett',
        'Jimmy', 'Joe', 'Joey', 'Johnny', 'Jojo', 'Joker', 'JR', 'Julius', 'Kane', 'Kato',
        'Kid', 'Killian', 'King', 'Klaus', 'Koba', 'Kobe', 'Koda', 'Koko', 'Kona', 'Kosmo',
        'Kramer', 'Kujo', 'Laddie', 'Lazarus', 'Lefty', 'Leo', 'Levi', 'Lincoln', 'Linus', 'Logan',
        'Loki', 'Lou', 'Louie', 'Louis', 'Lucas', 'Mac', 'Macho', 'Mack', 'Major', 'Max',
        'Maximus', 'Maverick', 'Mercle', 'Merlin', 'Mickey', 'Midnight', 'Mikey', 'Miko', 'Miles', 'Miller',
        'Milo', 'Mister', 'Mojo', 'Monster', 'Montana', 'Montgomery', 'Monty', 'Moose', 'Morgan', 'Moses',
        'Mouse', 'Mr Kitty', 'Mugsy', 'Murphy', 'Napoleon', 'Nathan', 'Nero', 'Newton', 'Nike', 'Nobel',
        'Norton', 'Oakley', 'Obie', 'Odie', 'Oliver', 'Onyx', 'Opie', 'Oscar', 'Otis', 'Otto',
        'Oz', 'Ozzie', 'Ozzy', 'Pablo', 'Paco', 'Paddington', 'Paddy', 'Parker', 'Patch', 'Patches',
        'Pedro', 'Pepe', 'Pete', 'Peter', 'Petey', 'Phantom', 'Picasso', 'Pierre', 'Piglet', 'Plato',
        'Pluto', 'Pogo', 'Pokey', 'Poncho', 'Pongo', 'Porter', 'Prince', 'Puck', 'Pugsley', 'Quinn',
        'Rags', 'Ralph', 'Ralphie', 'Rambler', 'Rambo', 'Ranger', 'Rascal', 'Rebel', 'Red', 'Reggie',
        'Remy', 'Rex', 'Rexy', 'Rhett', 'Ricky', 'Rico', 'Riggs', 'Riley', 'Rin Tin Tin', 'Ringo',
        'Ripley', 'Rocco', 'Rock', 'Rocket', 'Rocko', 'Rocky', 'Roland', 'Rolex', 'Roman', 'Romeo',
        'Roscoe', 'Rover', 'Rudy', 'Rufus', 'Ruger', 'Rusty', 'Sage', 'Sailor', 'Salem', 'Sampson',
        'Samson', 'Sarge', 'Sawyer', 'Schotzie', 'Schultz', 'Scoobie', 'Scooby', 'Scooby-doo', 'Scooter', 'Scottie',
        'Scout', 'Scrappy', 'Shadow', 'Shady', 'Shaggy', 'Sherman', 'Shiner', 'Shorty', 'Simon', 'Skipper',
        'Skippy', 'Slick', 'Slinky', 'Sly', 'Snoopy', 'Spanky', 'Spencer', 'Spike', 'Spud', 'Stanley',
        'Sterling', 'Stich', 'Stuart', 'Tank', 'Tanner', 'Taz', 'Teddy', 'Tex', 'Thor', 'Thunder',
        'Timmy', 'Tiny', 'Tom', 'Tommy', 'Trigger', 'Troy', 'Tucker', 'Turbo', 'Turner', 'Tux',
        'Tyler', 'Tyson', 'Vinnie', 'Vinny', 'Vito', 'Walter', 'Wayne', 'Webster', 'Wesley', 'Willie',
        'Wilson', 'Winston', 'Wizard', 'Wolfgang', 'Wolfie', 'Woody', 'Wrigley', 'Wyatt', 'Yoda', 'Yogi',
        'Yogi-bear', 'Yukon', 'Zack', 'Zeke', 'Zeus', 'Ziggy', 'Zorro'
    ];

    private array $femaleNames = [
        'Abbey', 'Abbie', 'Abby', 'Abigail', 'Addie', 'Aggie', 'Allie', 'Ally', 'Amber', 'Amie',
        'Amy', 'Annie', 'April', 'Ashley', 'Athena', 'Autumn', 'Baby', 'Baby-doll', 'Babykins', 'Barbie',
        'Beauty', 'Bebe', 'Bella', 'Belle', 'Bessie', 'Birdie', 'Bitsy', 'Bizzy', 'Blanche', 'Blondie',
        'Blossom', 'Bonnie', 'Brandi', 'Brandy', 'Bridgett', 'Bridgette', 'Brie', 'Brindle', 'Brit', 'Brittany',
        'Brodie', 'Brook', 'Brooke', 'Brownie', 'Buffie', 'Buffy', 'Butter', 'Butterball', 'Buttercup', 'Buttons',
        'Cali', 'Callie', 'Cameo', 'Camille', 'Candy', 'Carley', 'Casey', 'Cassie', 'Cha Cha', 'Chanel',
        'Chelsea', 'Cherokee', 'Chessie', 'Cheyenne', 'Chi Chi', 'Chic', 'Chiquita', 'Chivas', 'Chloe', 'Chrissy',
        'Cinder', 'Cindy', 'Claire', 'Cleo', 'Cleopatra', 'Clover', 'Coco', 'Cocoa', 'Crystal', 'Cutie',
        'Cutie-pie', 'Daisey-mae', 'Daisy', 'Dakota', 'Darby', 'Darcy', 'Daphne', 'Dee Dee', 'Destini', 'Diamond',
        'Diva', 'Dixie', 'Dolly', 'Dottie', 'Duchess', 'Easter', 'Ebony', 'Echo', 'Ellie', 'Emily',
        'Emma', 'Emmy', 'Erin', 'Eva', 'Faith', 'Fancy', 'Fergie', 'Fifi', 'Fiona', 'Flower',
        'Gabriella', 'Genie', 'Georgia', 'Gigi', 'Gilda', 'Ginger', 'Ginny', 'Girl', 'Gizmo', 'Godiva',
        'Goldie', 'Grace', 'Gracie', 'Greta', 'Gretchen', 'Gretel', 'Gretta', 'Gucci', 'Gypsy', 'Hailey',
        'Haley', 'Hallie', 'Hanna', 'Hannah', 'Happy', 'Heather', 'Heidi', 'Holly', 'Honey', 'Honey-Bear',
        'Hope', 'India', 'Indy', 'Iris', 'Isabella', 'Isabelle', 'Itsy', 'Itsy-bitsy', 'Ivory', 'Ivy',
        'Izzy', 'Jackie', 'Jade', 'Jamie', 'Jasmine', 'Jazmie', 'Jazz', 'Jelly', 'Jelly-bean', 'Jenna',
        'Jenny', 'Jersey', 'Jess', 'Jessie', 'Jetta', 'Jewel', 'Jewels', 'Jingles', 'Jolie', 'Jolly',
        'Jordan', 'Josie', 'Joy', 'Judy', 'June', 'Kali', 'Kallie', 'Karma', 'Kasey', 'Katie',
        'Kayla', 'KC', 'Keesha', 'Kellie', 'Kelly', 'Kelsey', 'Kenya', 'Kerry', 'Kiki', 'Kira',
        'Kissy', 'Kitty', 'Kiwi', 'Kyra', 'Lacey', 'Lady', 'Ladybug', 'Laney', 'Layla', 'Lexi',
        'Lexie', 'Lexus', 'Libby', 'Lightning', 'Lucy', 'Lulu', 'Luna', 'Macy', 'Maddie', 'Madison',
        'Maggie', 'Mandi', 'Mandy', 'Mango', 'Marble', 'Mariah', 'Marley', 'Mary', 'Mary Jane', 'Mattie', 'Maxine', 'May', 'Maya', 'Mckenzie', 'Meadow', 'Megan', 'Meggie', 'Mercedes',
        'Mia', 'Miasy', 'Millie', 'Mimi', 'Mindy', 'Ming', 'Mini', 'Minnie', 'Mischief', 'Misha',
        'Miss Kitty', 'Miss Priss', 'Missie', 'Missy', 'Misty', 'Mitzi', 'Mitzy', 'Mocha', 'Mollie', 'Molly',
        'Mona', 'Muffy', 'Nakita', 'Nala', 'Nana', 'Natasha', 'Nellie', 'Nemo', 'Nena', 'Nikita',
        'Nikki', 'Nina', 'Olive', 'Olivia', 'Ollie', 'Onie', 'Pandora', 'Patsy', 'Patty', 'Peaches',
        'Peanut', 'Pearl', 'Pebbles', 'Penny', 'Phoebe', 'Phoenix', 'Piper', 'Pippin', 'Pippy', 'Pixie',
        'Polly', 'Pookie', 'Pooky', 'Poppy', 'Precious', 'Presley', 'Pretty', 'Pretty-girl', 'Princess', 'Prissy',
        'Queen', 'Queenie', 'Raven', 'Rosa', 'Rosie', 'Rosy', 'Roxanne', 'Roxie', 'Roxy', 'Ruby',
        'Ruthie', 'Ryder', 'Sabine', 'Sable', 'Sabrina', 'Sadie', 'Sally', 'Salty', 'Sam', 'Samantha',
        'Sammy', 'Sandy', 'Sara', 'Sarah', 'Sasha', 'Sassie', 'Sassy', 'Savannah', 'Scarlett', 'Shasta',
        'Sheba', 'Sheena', 'Shelby', 'Shelly', 'Sienna', 'Sierra', 'Silky', 'Silver', 'Simone', 'Sissy',
        'Sky', 'Skye', 'Skyler', 'Smoke', 'Smokey', 'Snickers', 'Snowball', 'Snowflake', 'Snowy', 'Snuggles',
        'Sophie', 'Sophia', 'Sparkle', 'Star', 'Starr', 'Stella', 'Stormy', 'Sugar', 'Sugar-baby', 'Summer',
        'Sunday', 'Sunny', 'Sunshine', 'Susie', 'Susie-q', 'Suzy', 'Sweetie', 'Sweetie-pie', 'Sweet-pea', 'Sydney',
        'Tabby', 'Tabetha', 'Taffy', 'Tally', 'Tammy', 'Tango', 'Tara', 'Tasha', 'Taylor', 'Tequila',
        'Tess', 'Tessa', 'Tessie', 'Thelma', 'Tiffany', 'Tiger', 'Tigger', 'Tiggy', 'Tiki', 'Tilly',
        'Tippy', 'Toni', 'Tootsie', 'Topaz', 'Tori', 'Trixie', 'Trinity', 'Tuesday', 'Twiggy', 'Twinkle',
        'Violet', 'Willow', 'Winnie', 'Winter', 'Xena', 'Yaka', 'Zena', 'Zoe', 'Zoey', 'Zoie'
    ];

    /**
     * Returns a random name from all names (male and female)
     *
     * @return string
     */
    public function getRandomName(): string {
        $allNames = array_merge($this->maleNames, $this->femaleNames);
        return $allNames[array_rand($allNames)];
    }

    /**
     * Returns a random male name
     *
     * @return string
     */
    public function getRandomMaleName(): string {
        return $this->maleNames[array_rand($this->maleNames)];
    }

    /**
     * Returns a random female name
     *
     * @return string
     */
    public function getRandomFemaleName(): string {
        return $this->femaleNames[array_rand($this->femaleNames)];
    }

    /**
     * Returns all male names
     *
     * @return array
     */
    public function getMaleNames(): array {
        return $this->maleNames;
    }

    /**
     * Returns all female names
     *
     * @return array
     */
    public function getFemaleNames(): array {
        return $this->femaleNames;
    }

    /**
     * Returns all names (male and female)
     *
     * @return array
     */
    public function getAllNames(): array {
        return array_merge($this->maleNames, $this->femaleNames);
    }

    /**
     * Returns the total count of all names
     *
     * @return int
     */
    public function getCount(): int {
        return count($this->maleNames) + count($this->femaleNames);
    }

    /**
     * Returns the count of male names
     *
     * @return int
     */
    public function getMaleCount(): int {
        return count($this->maleNames);
    }

    /**
     * Returns the count of female names
     *
     * @return int
     */
    public function getFemaleCount(): int {
        return count($this->femaleNames);
    }
}
