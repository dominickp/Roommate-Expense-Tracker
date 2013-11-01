#Roommate Expense Tracker
The goal of this application is to satisfy the requirements for Project 2 in a fun way that is also useful. Using this application, you will be able to register an apartment, add roommates, view your monthly expenses, add new one time or recurring expenses, log new payments made to other roommates, and see a tally of how much you owe each of your roommates and how much they owe.

##Background
I recently moved to be closer to Harvard for classes. I moved into an apartment with 3 other roommates and we soon realized how convenient it was to have one person buy something and to just split it with them later. Some items are recurring and fixed price, like a cable bill. Other expenses are random but necessary like toilet paper, furniture, and booze for parties. If a roommate pays for a new expense, the other roommates can even out their debt by paying the purchaser or by purchasing a needed item themselves. We all do our part but tallying our receipts is a huge pain. When we moved in, I created an Excel spreadsheet which I now call "the spreadsheet from hell". It's poorly organized, hard to look at, and even harder to understand. This project is an attempt to organize and impliment the math used in that spreadsheet to give roommates a clear and unambiguous balance that they owe or are owed with their fellow roommates.

##Project Requirements
My project does slightly deviate from the requirements of P2. Firstly, I asked if I could deviate from the framework (Piazza CID 194), I went with Symfony2. This was my first experience with any PHP framework and it was a huge challenge to get up and running. Very frustrating at times, but I'm glad I went this route because I have learned a ton in just the past few weeks. I also asked if I could deviate from the microblog type site in favor for something a little more original but has similar core components (Piazza CID 288). Below I have listed the feature requirements and how they coorespond to this application.

- Sign up
- Log in
- Log out
- Add posts **(Expenses)**
- See a list of all other users **(Roommates)**
- Follow and unfollow other users **(Linking your account to an Apartment)**
- View a stream of posts from the users they follow **(Browse Expenses)**
- +1 other feature of your choosing - **(Add and browse Payments)**
- +1 other feature of your choosing - **(Calculate a Balance based on Expenses, Payments, and the # of roommates tied to your Apartment)**
- Browse payments
- Edit account
- Edit expenses

####Note for the person grading this:
The majority of my code can be found in my bundle here: `/src/Dominick/RoommateBundle/`

##Todo
- Add Apartment password so random people can't join your apartment and screw with your expenses
- Fix register with short password, lack of error
- Make home page have better stats
- Edit payments

####Thanks
Huge thank you to TigerC10 for sticking with me through those really tricky Symfony/Doctrine issues. I would have crammed so much junk in my controllers if you hadn't been there to help me as much as you did.
