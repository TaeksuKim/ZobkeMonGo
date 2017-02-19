## Web APIs

### ```/json/login.php```

Get User Key using login Id and password.

#### Input

 - loginId : Login Id
 - loginPassword : Password

#### Output

 - status : OK or Fail
 - loginId : Login Id
 - userName : User's name
 - userKey : Key for authentication

### ```/json/list.php```

List up all monsters

#### Input

 - userKey : Key for authentication

#### Output

 - status : OK or Fail
 - monsters : Array of monsters
   - monsterId : Id of monster (Integer, 1-299)
   - number : Number of monster (String, '001'-'299')
   - name : Name of monster
   - ownerCount : Number of owners
   - isNew : 'Y' if this monter is registerd within 24 hours, 'N' otherwise

### ```/json/mylist.php```

List up my monsters

#### Input

 - userKey : Key for authentication

#### Output

 - status : OK or Fail
 - monsters : Array of monsters
   - id : Id of monster (Integer, 1-299)

### ```/json/ownerlist.php```

List up owners of monsters

#### Input

 - userKey : Key for authentication
 - monsterId : Id of monster

#### Output

 - status : OK or Fail
 - owners : Array of owners
   - loginId : Login Id
   - userName : User's name

### ```/json/have.php```

Toggle possession of a monster. Be sure that `GET` method **MUST** be used for this API.

#### Input

 - userKey : Key for authentication
 - monsterId : Id of monster

#### Output

 - status : OK or Fail
 - owners : Array of owners
   - loginId : Login Id
   - userName : User's name

