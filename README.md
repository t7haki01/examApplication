# Version of tools used
1. Symfony CLI version v4.9.0 (c) 2017-2019 Symfony SAS
2. PHP 7.3.9 (cli)
3. Composer version 1.7.2
4. Nodejs v12.13.0
5. NPM 6.12.1

# SCENARIO
- Online Exam application, where teacher/student user have already account in school authentication system and teacher can make questions and exams for student so student can take exams.

* User A - Teacher
1. User A can make exams(With selection or randomly), questions
2. User A can edit questions
3. User A can delete exams, questions
4. User A can define exams target
5. User A can check exams results
6. User A can sign in/out

* User B - Student
1. User B can take exams (targeted)
2. User B can check exam results (taken)
3. User B can sign in/out

# Deploy
https://shielded-oasis-74766.herokuapp.com/
ex) teacher1:test1, student1:test1


# TODO
1. Make sure that Required Symfony, PHP, Composer, Nodejs and NPM installed corresponding version.
2. "composer install" and "npm install" required to install dependencies
3. Build assets with webpack encore, with npm script or yarn depends on which one used
4. Set proper values in ".env" files, especially database configurations
5. Create schema and data fixtures if necessary with doctrine console
6. Depends on env (dev or prod) and hosting (shared or localhost), Do the rest!


* If not using symfony CLI, using vagrant/virtualbox(or some other VM), then Install the vagrant and VM supported version also vagrant plugin especially, if OS is Windows(*Recommend to run in administrator)
* Possible to take a reference 4.1 branch readme
- vagrant plugin install vagrant-vbguest
- vagrant plugin install vagrant-winnfsd
- vagrant up
- vagrant ssh
- cd /vagrant
- composer create-project symfony/skeleton symfonyApp
- http://localhost:4567/
