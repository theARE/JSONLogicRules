<big>JSON Logic Rules  
</big>

This project in and of itself doesn't do much, but it's designed to be a subset of a larger application.  
  
The idea is that a semi technical user could "program" the logic of a form based application themselves.  
  
I'm sure everyone has set up message filters in an email client - to say:
  
 "if subject contains 'something' move messages to folder 'other' "

The idea here is for an UI similar to that that would construct a formatted JSON object describing the logic.  
  
That logic could be used to control front end operations in javascript (such as showing and hiding inputs based on previous answers) adding up subtotals to a total field etc.  

a simple example:

{"rules":
	[
	{"if":
		[	{"id":"one-and-two-null"},
			{"#one":[{"value":"!=null","observe":"change"}]},
			{"join":"and"},
			{"#two":[{"value":"!=null","observe":"change"}]},
			{"action":{"show":"#eight"}},
			{"action":{"show":"label[for='eight']"}}
		]
	}
	]
}

basically the above means monitor input #one and #two and if both are not null then hide #eight and it's associated label.

roughly the format of the rule part is:   
    what to check, what to check for, how to check      
the action part is:
    action  what to do, what do it on 

Joins and id are optional - id is mainly there to make manipulating the JSON object easier.

The javascript just reads the rules and interprets those rules but otherwise doesn't contain any form logic in itself.  
  
The javascript version as stands requires jQuery - but could probably be done without it.  
  
A rudimentary PHP example is also included - this is not as complete as the Javascript version - but the idea being that after each question set a "logic set" would evaluate the form input based on the defined rules and decide what to do next - redirect on to a different question set, add to an evaluation queue or email the user or moderator.

While researching this the only two similar thing that I came across was   
Chain Commander  
https://www.npmjs.org/package/chain-commander  
  
and an aborted project  
JSON Rules  
http://code.google.com/p/jsonrules/wiki/RuleLanguage

The rule structures as I've defined them suits the project I'm developing at the moment, but I'd be very happy to work with others to expand and develop this concept further.  
  
  
