# **//\*\*PLEASE REVIEW AND COMPARE AGAINST CURRENT CODE\*\*//** **Homepage Animation & Section Transition Spec (Sections 1–8, Pre-About)**

---

## **Section 1: Hero / Intro**

* **Layout:** Fullscreen (100vh), no gutter, content centered.

* **Background:**

  * **p5.js haze** (animated, rendered ONCE, fixed/bottom-most layer)

* **Pinning:**

  * **YES** (GSAP ScrollTrigger, `pin: true`)

  * **Pin Duration:** From top of page until *About* section scrolls in (continuous pin across all pre-about sections)

  * **How:** Pin `.hero-section` with ScrollTrigger (start: top, end: \+=\[About section offset\])

* **Entry Animation: 100%-0** [https://gist.github.com/Hurtwaffles/6cc7f12d41ae1ed5a35233e264b075ff.js](https://gist.github.com/Hurtwaffles/6cc7f12d41ae1ed5a35233e264b075ff.js) 

  * **Type:** GSAP \+ SplitText

  * **Effect:** Each headline line reveals upward (mask up, with subtle stagger between lines)

  * **When:** After loader finishes

* **Exit Animation: 0-100%** [https://gist.github.com/Hurtwaffles/6cc7f12d41ae1ed5a35233e264b075ff.js](https://gist.github.com/Hurtwaffles/6cc7f12d41ae1ed5a35233e264b075ff.js) 

  * **Type:** GSAP \+ SplitText

  * **Effect:** Each line animates up and out (same mask, now hiding text upward)

  * **Trigger:** User begins scroll past hero copy

* **Special Note:**

  * The section itself remains pinned; only the copy animates out—background stays fixed.

    ---

    ## **Section 2: "Select Projects" Title**

* **Layout:** Transparent section, headline only, top-left aligned.

* **Background:**

  * Remains the *p5.js haze BG* (still pinned, still bottom-most).

* **Pinning:**

  * **YES** (GSAP ScrollTrigger, `pin: true`)

  * **Pin Duration:** From Section 2 entry until first project (Section 3\) image/title reveal.

* **Entry Animation:**

  * **Type:** Fixed top left start \= GSAP \+ SplitText

  * **Effect:** Headline reveals line-by-line, bottom-up mask.

* **Exit Animation:**

  * **Type:** GSAP \+ SplitText

  * **Effect:** Title animates out (mask up), timed to coincide with the first project’s title reveal [https://gist.github.com/Hurtwaffles/28c452d49fd2d33a0391871591e18aa6.js](https://gist.github.com/Hurtwaffles/28c452d49fd2d33a0391871591e18aa6.js) 

  * **Trigger:** At 75% of project image reveal in Section 3, swap/mask animation swaps out “Select Projects” for the project title, both in same x/y position for seamless transition.

    ---

    ## **Sections 3–7: Project Sections (Repeatable Module)**

**For each featured project, repeat this module:**

### **A. Pinning & Layout**

* **Pinned:** **NO** (normal document flow; only the background remains pinned).

* **Layout:**

  * 100vh viewport, project image centered (8/12 grid on desktop, responsive).

  * Project title: Top-left, in same position as "Select Projects" for seamless swap.

    ### **B. Entry Animation**

* **Project Image:**

  * **Type:** GSAP timeline

  * **Effect:**, masked bottom-up (rect mask), fixed center, holds \[reference “how neat is that” [https://gist.github.com/Hurtwaffles/5e4be3bd9c69972bbfa45fd900d176e1.js](https://gist.github.com/Hurtwaffles/5e4be3bd9c69972bbfa45fd900d176e1.js)

  * **Trigger:** on section begin, reveals from center of view frame.  
      
* **Project Title:**

  * **Type:** GSAP \+ SplitText

  * **Effect:** At 75% of image reveal, “Select Projects” title masks up/out, project title masks up/in (perfect overlap for seamless swap).

  * **Trigger:** ScrollTrigger timeline label chained to image reveal.

    ### **C. Project Indicator Dot**

* **Element:** `.indicator-dot` (right gutter, 16px from edge, vertically centered)

* **Color:** rgb(242, 242, 243), Hover: override per-project with `.dot-[project]` or `data-project`.

* **Animations:**

  * **Appear:** mask up animation (`onEnter`).

  * **Sticky:** stays in position, based on number of dots (1-6 projects).

  * **Hover:** GSAP scale up, optional label fade/color highlight.

  * **Click:** slight scaledown (.97) GSAP `.scrollTo()` to the project section.

  * **Disappear:** mask out when scroll above start animation point.

* **If on project 1, 1 dot visible, project 5, 5 dots, project 6, 6 dots**

  ### **D. Exit Animation**

* **Project Image & Title:**

  * **Type:** GSAP timeline (reversed from entry).

  * **Effect:** reverse animation

  * **Trigger:** Scroll above section

* **Dot:** Same disappear logic.

  ---

  ## **Section 8: Final Project Section (Pre-About “Sticky”)**

* **Layout:** Same as above.

* **Pinning:** **YES**—remains sticky (`pin: true`) as user scrolls into About.

* **Pin Duration:** Until About section fully covers it.

* **Dot:** Stays sticky until About takes over.

* **Exit Animation:**

  * When About scrolls in, final project section unpins, can fade/mask up out.

    ---

    ## **GENERAL ANIMATION NOTES**

* **Pinning:** All via GSAP ScrollTrigger, never Semplice pin tool.

* **Reveal Masking:** All text reveals use GSAP \+ SplitText, always mask up. Images use clip-path/overflow-hidden.

* **Dot Indicators:** Fixed position, one per project, controlled by ScrollTrigger.

* **Title Swaps:** Always mask, no fades—motion direction is always up.

* **Stagger:** Multi-line text staggered for energy but still controlled.

* **Mobile Responsive:** All logic and pinning/timelines must be responsive.

* **Dot Nav:** Not shown on mobile (desktop only).

  ---

  ## **ORDER OF EVENTS (SCROLL FLOW)**

1. Hero intro animates in

2. Scroll triggers hero copy to mask up/out

3. "Select Projects" animates in, fixed positon top left, pinned

4. User scrolls → Project 1 image animates in (center), at 75% image visible, "Select Projects" title masks out, project title masks in, dot appears

5. Project 2 (same flow)

6. ...

7. Final project is sticky as About overlaps and scrolls over

8. About fully covers, everything unpins, standard scrolling resumes

   ---

   ## **DEV/APPLICATION INSTRUCTIONS //\*\*REDO OR ALIGN\*\*//**

* **Section 1:** `.hero-section` (pinned); headline uses `.brandon-hero-title` and SplitText

* **Section 2:** Transparent, headline only; also uses `.brandon-hero-title` and SplitText

* **Section 3–7:** Each project gets `.brandon-project-section` and `.indicator-dot` for dot

* **Section 8:** Final project, sticky until About

* **No** manual SplitText classes needed; handled in JS

* **All pinning and dot logic** handled by custom JS with GSAP \+ ScrollTrigger

# **//\*\*BEGIN NOW\*\*//**

# **Homepage Animation Build Progress: Annotated Status** ---

![][image1]

## **Section 1: Hero / Intro**

**Target:**

* Section 1 background pinned

* Animated p5.js haze background (fixed, bottom layer)

* Headline animates in line-by-line (GSAP \+ SplitText, mask up on reveal) \[reference: [https://gist.github.com/Hurtwaffles/6cc7f12d41ae1ed5a35233e264b075ff.js](https://gist.github.com/Hurtwaffles/6cc7f12d41ae1ed5a35233e264b075ff.js) \- Class: .title-line\]  
* On scroll, headline animates out line-by-line (mask up/out) \[reference // opposit direction: [https://gist.github.com/Hurtwaffles/6cc7f12d41ae1ed5a35233e264b075ff.js](https://gist.github.com/Hurtwaffles/6cc7f12d41ae1ed5a35233e264b075ff.js) \- Class: .title-line\]

* Section itself remains pinned

**Current Status:**

* **p5.js haze background** is implemented and pinned (not sure duration, but should be until “about” section)

* **GSAP SplitText headline animation** not working

* **Pinning** is handled via JS, but need to verify pin duration holds through to About section and does not unpin between 1–8

**Outstanding:**

* Final QA on pin duration (ensure it stays pinned through all pre-About sections)

* Confirm headline exit animation triggers correctly so ‘Select Projects’ can load in at pinned upper left location

---

## **![][image2]Section 2: “Select Projects” Title**

**Target:**

* Background transparent, headline-only section (top-left aligned)

* Shares pinned background (inherits from Section 1\)

* Animates in using GSAP \+ SplitText (mask up) at upper left location, as if part of section 1\.

* Pin stays active until first project appears

**Current Status:**

* **Section structure and pinning**: Set up structure, pinning not working

* **Headline animation**: not working properly

* **Exit triggers for title swap**: not implemented \[reference: [https://gist.github.com/Hurtwaffles/28c452d49fd2d33a0391871591e18aa6.js](https://gist.github.com/Hurtwaffles/28c452d49fd2d33a0391871591e18aa6.js) \- mask transition from one word to another\]

**Outstanding:**

* Polish timing of title swap for seamless “mask out/mask in” (should overlap at same x/y)

* Verify pin duration and stacking order through this section

---

## **Sections 3–7: Project Sections**

**Target:**

* Each project is a module (normal flow, not pinned)

* Project image animates in (mask up) at same exact spot, fixed size and location \[reference: [https://gist.github.com/Hurtwaffles/17f8cacad27a6c788f4f85f1d94f1ee3.js](https://gist.github.com/Hurtwaffles/17f8cacad27a6c788f4f85f1d94f1ee3.js) \- .section\]

* Project title animates in (mask up), swapping with “Select Projects” title \[reference: [https://gist.github.com/Hurtwaffles/28c452d49fd2d33a0391871591e18aa6.js](https://gist.github.com/Hurtwaffles/28c452d49fd2d33a0391871591e18aa6.js) \- mask transition from one word to another\]

* Dot indicator appears, stays fixed/right

* Dot: GSAP mask in, sticky position, click-to-scroll

* Exit: next project mask up, new project title mask up/old project title out, new dot appears below

**Current Status:**

* **Section 1 background pins in place**

* **Section 1 animation intro not working, outro not working**

* **Dot nav:** .indicator-dot logic started (review current code to confirm), appears tagged CSS Class section; dot is fixed, scales/opacity animates, click/scroll links in place

* **Exit animation:** Reverse timeline for out is conceptually there, not yet fully debugged


---

## **//\*\*NOT ADDRESSING ANYTHING BELOW HERE CURRENTLY\*\*//** **Section 8: Final Project Section (Pre-About “Sticky”)**

**Target:**

* Final project section remains sticky/pinned until About scrolls over

* Dot indicator stays visible while pinned

* When About reaches threshold, section unpins, can fade/mask up/out

**Current Status:**

* **Pin logic:** Not fully implemented yet—final project is currently normal flow

* **Dot behavior:** Not yet sticky on this last section

* **Exit logic:** Not hooked up

**Outstanding:**

* Add ScrollTrigger pin for final project section

* Sync unpin/fade logic to About section arrival

---

## **//\*\* NEED CONFIRM WITH CODE EVERYTHING BELOW WITH ANALYZATION\*\*//** **General/Global Animation Logic**

**Implemented:**

* GSAP ScrollTrigger \+ SplitText for reveals

* P5.js haze background, injected and managed

* Button/logo handlers, smooth scroll, SPA transitions (JS)

* Dot nav and project swap logic (foundation set)

**Outstanding:**

* Responsive/mobile: Dots should hide on mobile, verify all ScrollTrigger behavior on different screens

* Stagger and mask polish: Confirm all reveals/outros use upward mask, not fades, with subtle delays for multi-line text

* Test pinning stacking/overflow—no double pins, pin durations do not conflict

* All timing/sync: Scroll progression should feel seamless, never abrupt

---

## **What to Do Next**

1. **QA Section 1/2 pinning duration**—verify stays pinned until About, not unpinned mid-flow

2. **Perfect “Select Projects” → project title swap**—GSAP timeline must have overlapping masks for seamless headline change

3. **Generalize project logic** for multiple sections; ensure each one triggers dot/nav logic and entry/exit masks in/out as described

4. **Implement/verify Section 8 sticky logic** (final project pin until About)

5. **Mobile/responsive testing**—hide dots, verify ScrollTrigger, ensure all timing is robust on tablet/mobile

6. **Animation QA:** Markers for ScrollTrigger enabled during dev; review scroll experience and stacking context

7. **Code/Style polish:** Clean up and document section classes (.brandon-hero-title, .brandon-project-section, .indicator-dot)

[image1]: <data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAloAAAExCAYAAACkgAzuAABDbElEQVR4Xu2dd98kVZn+f29gJUye55k8IyJBQMkCKigrBlgUcVEkCYikMYCKGBCUjAxRlKAiYk6YMaxiDoiiuCu6KrqmVVH3BdSPq8rTU885190zNdP1xO8f309XX3361KlTp6qurr7vOv/vUY/atgIAAACA0fP/cgEAAAAARgNGCwAAAKAnMFoAAAAAPYHRAgAAAOgJjBYAAABAT2C0AAAAAHoCowUAAADQExgtAAAAgJ7oZLSOOuro6o477qw+9KEPV1de+dbic3H66WdOeH/zzbdWH/nIxwYk/R//+L/B8tjYsup97/tA9Zvf/LZ+f9VVV9tyAAAAMLvYbbc96teHHvpN/fqKV5xT7b7746t77vnaBA/wwQ9+uPrMZz5XHXvscfX79Nnf//6Pos6cdj0nnHBi7WW+/e3vVmedtX7w+dVXb6iOP/6E+v1PfvLAhO/Lv3zrW9+uPvrRiV5mc+hstP7lX7Z5ZHmb0ACVRuuWwXeSdthhz6wWLVoyeN/UtbEMRgsAAGDucMQR/1b96Ef3V9tss221YMGigS/Q+0MOeVq9nPsPvW6uRzj33FdXDzzw03pZRivV9cc//qlasWLVI2Zv9/p9Mm250RInn3zKI+3ZrtA3RUej9bza8W3YcG11441vKz4XzmhdfvkVj3BltXTpeK2pY7SR2tj0vv2dq6566z+/c0XxGQAAAMwuPv/5u6sjj3xudeWVV1X5zRx9Js+w0VhtNFx/+ctfi7oc6ftalveQl9HdqTe84Y3VhRde9M86t62+8pWv1suR0dp22+0LfVN0NlpqrIhu1Z1++hkT3m+8o7VR+8EPflh99rOfG9TRrksbIaOV1oPRAgAAmN2kGzC/+tVDVdto6Q7S/vsfMPAEp5zykvrzVEZ/MZ5xxsQbPDm//OWvas/xhS98sX6f7mglj7FkydLqwAMPGrSjvf558+YP6pkko3V09etfP1THUn36058Z6G0zJKOVXOfdd3+xNlrpfSqnhmoD//rXh//5vW2q3/3u94PPZbSSu8RoAQAAzG7+8Ic/Tri5sv3286qHH/7bP2/EpNCihoceauK5kyl68MGfF/W1SSZOjI8vq42WvIz0O+98X13Hn//8l+qLX/xSdfDBh9Tf+f73763b1PYgk2K0AAAAAGDzwWgBAAAA9ARGCwAAAKAnMFoAAAAAPYHRAgAAAOgJjBYAAABAT2C0AAAAAHoCowUAAADQExgtAAAAgJ7AaAEAAAD0BEYLAAAAoCcwWgAAAAA9MSlGS7Nvi1yH0aC+3W67eVZfsGBRoYuFC5dYbfHisUJfsmTc6uPjK+rPcn1sbPkjell+6dJlhSYWL15aaGLhwsWFJubPX1ho2v5587y+/fYLCl0Tg7rJQbuO1W222d6Wd3Vvie7qHhVN3WX9UVvcGNtYT6nPBLru77lE1C9ufET9GOnR8acx5saZNFfP9tvPt/XMm1ce81uiu3OQzoXuXLZs2cpq1aq1hb5ixZpq5cpSX7v2MVZfs+bRhSZWrVpXaEL155pYvnxVoYmxsbLtOne6c7zOnU6X1kVXf7k+1jVkwYKyj9WXudbo5TZpXKieXBe6FuWa2uLqHx9f/khfri50bU98nfN6rk2K0QIAAACYi2C0AAAAAHoCowUAAADQExgtAAAAgJ7AaM0SXKCo2G67+YUmXABpU74MRI1wwY3D9Cgwf/58r7vgdqEA2FwTXdo+KqJ+70pUz6h0R1S2qw6zk2h/x7o/pziiOroSHfPR+S3Su9bjznHaJhfYLd1trwKpnR4lB7lAbREFvUcB4osW+fpdgpRwbYy2SVrUZ47o3O+SEETU9qjPov3kEqqku/W67RS6PrnP3PUJozVLcDtcGWVupwtnYqS5gS/NlV+0aIk9sUR6dCC7wS1cWyJ9WHahO4k22YLlCSE6gURE5Z22ZXrZxqj8sLZ4vVvGpCsLs5dof7vxobHkdV14y+NPPwDdj0Cdr9w5K8oujHSnDdNdW4S7UOt85S74UTabNGeGlF3ozNPq1T7rMNa7ZSMquy7XdE5durTMoFP/um3V+d2d4/Vj2v2gVlnXx6rb9bHLWm/0so0aYy4LNCovzWUGRvtPbXR9oDbqWpfrTsNoAQAAAPQERgsAAACgJzBaAAAAAD2B0QIAAADoCYzWLCcK/owCXSPd4YLMh+kuoF644Ndh9UR6tK0zmXh/eD0uXxKVjXSI+2ZUerRfp4LoeIr06Lh0NNvvtzXum5JonVEdkR7V4wL5RXTOctlsEcqUc+1xwdTCBWoP06MA8TgpyeuujU7bEqJ+79q/LjBfdK3fBeZHuOB+4Y4PjNYsx+104QagDp5IdweWsvycrsHq1uuyBVP5XBPRweCyC4Vru3BtnClEbe+iO21LdIj7pm99KnDHsIiPS6+743JYdmGk55qIzh1R2yM9umi6+lXWZUqrjS7LTT8w3TlLGYfunOgyFIWbR3GYHtXj2qhx53QR9YHbJ7FejgHh6hZdDVVkTl0GZLT/pLvHRGjMOMOtfefGk9smjBYAAABAT2C0AAAAAHoCowUAAADQExgtAAAAgJ7AaM1RXNCttC66AhydrgBBpzttmB4RlY/0KLtpLuH6JtqvToOGqG8i3QXLChccrDqi8q5+p20J0TpdG4ULdlZbXFaxdBfYrAw3F5A8bGoX185Ij4Lb420qA5iFC6ZWEPTixeUUMQqkdlPqqKwrv2rVOhuAHgWxR3qUdTg25rMOo4Bvl3XY7Neyb9SPri+bwHG3P/y1QmPJ6a7uRvf71Y3JSFcdbqyqrBt7aourR+PA6a6/MFpzFDe4I91pIjp43IE2rJ5Ij4jKd9XnOlG/RDrEfRPp0bHgsphUR1TeEa2zK9E6o4udu7jox4y7eKmNzsTIwLiMO5kyZ8xc3ake1w/RBTneprKNwrVFZd3cd9oeZ3qiuWI1F6Hrg2gy6FEZLdd29WFktNz+1phxfRkZLaeJqTBaaoszQ1qn2x/RNmlsON2NJYwWAAAAQE9gtAAAAAB6AqMFAAAA0BMYLQAAAICewGgBAGwmLnB3mO6CZYUL9h0WYOzqV6Cvq99pw3S3TuEChoXLFlTdLsg60qNMvEZ35Zfa9ke6C2IXLihduEBw4eYL1DpdYPqSJWN1JmGua3oYV/+yZaus7tYpXIaicP0r3H4Srm80vlzfRMHwXbMOnSbcuO5bl+ba0xx/5bZGx6X60ZUnGB4AYBJxJ/qGrrqjS9m4LV11d5EapjtUt68/0j3ROqM6Ij2ux+tReXdBjnCZp43u6+5e3utRH0T63GLz+yDqL6djtAAAAAB6AqMFAAAA0BMYLQAAAICewGgBAAAA9ARGCwBgM3GBrsN1H5DsgqaV3eUylpT95zIAlSXmdJclJtw6G92Xd9ORCGXX5ZrmiHNZcarD6cqsc9l1Y2PLa3Jd08y4eQc1zYxrZ5SJpwzAXNO+c22R7rILly9fXa1cubbQNc/hqlWlPj7ut0n94jIvXRuF237RdX+7IHlta6S7eqKsQ413N+bj5IfpQ9TGKJNS2+8SFNzxhNECAJh0yhO6cCf6iKjsqHR3cRHuAjtMd/VHF7Vheq6JyGS4C6CI2hjVE5V3F9Nh9TiiNkb9HulR30R6RNfyc52ov5yO0QIAAADoCYwWAAAAQE9gtAAAAAB6AqMFAAAA0BMYLQCAScYFNsdZh8ouLOeh0zx5LhNNGYC51uhlWeHqFi6bT7g59FTWzVGoul0GoMq68sOyDl17xsdX2O1ymY7CZRdqXyiTMNcV1Lxy5ZpCVybiihWlrnarPbkebav6xc11GGUdRvvJjaWpYlgyg9NnAlHbI92B0QIAmCZs7olbRBfYSI8y6LqWj3Wfcee2KbpIDdNzTbjHW4go+y8q78zasHq66o6oH6P9EelR38D0AaMFAAAA0BMYLQAAAICewGgBAAAA9ARGCwAAAKAnMFoAAD0RBSq7wGYFk7tg6miuQ2UXukw8lxXY6D6bLdK7ZC+q3U5XwPf8+WW2nOp29UdZh8r0c/VLd+13cxRGuvaFm7tQ+07zF+a6MgtdPcpo9FmHY3ZOQ2mu7dH+i4LnYfqD0QIAAADoCYwWAAAAQE9gtAAAAAB6AqMFAAAA0BMYLQAAAICewGgBAGwlUXZhpPusw3k267CZA7HUNfedmxvRZSIKl+EmliwpM+KEm6OwqafMilPbXWakMindHH1qi2vP8KzDcr3KFnTtXL16XaGJVavK7MJmrsMyi1D7TnMs5vr4+HKrS3NZispGdHMsqt/dHIhuO4UbMzAzwGgBAAAA9ARGCwAAAKAnMFoAAAAAPYHRAgAAAOgJjBZMCQqQXb/+vBoXzAowG4iC4SO6lI/KRnoUTL3ttn5ql7h8N70Lartrv9OEC8AfrpeB+cIlFYhom2Ld96UjqiPaVpi5YLRgSkgmK5F/DjCTiC6O0cVU2Xi5pou9MwLSnHGIsg7dnIDCZQuKRYvK7L+mHl/eZTWqfW69zRyIZT1ap1vvkiXjNbmujD5Xz6pV6+w8glHWoZvTUPvIZRFGWYfKiuyiK+PQbZPa7X5kurkhRTSWYPqD0YIpAaMFAABzAYwWTAkYLQAAmAtgtGDK0EMCV65cU+gAAACzBYwWAAAAQE9gtAAAeiIKku+qO6Kyse6DqePy3fSpCNbW1D+5NlwvkxCGlY+2Ne5Lrzuiuh/1KK/H5WG6g9ECANhKIpMRXcBdFqEy9FxGn3RXj7L83ByILvtPuLob3We5uQzIqLy2381dKN1lCzZzHXrdZSMqc889OkFzC7o+WLHChyS4OQ1lbNz8imJsrJyjUO12cxeq35cuLbMLta/d/o72n8skFdEYg+lvQjFaAAAAAD2B0QIAAADoCYwWAAAAQE9gtAAAAAB6AqMFALCZREG3UaCyC2IXLghadUS6q19B467+KIjd1S1cHcPKu8Bu4YLkm3rK8irryjd6GbTvNLF48VK7T6Lg9vHxFYUmXHC7cAH76i+XcNDoZXm1z7VR+9Tp0f7omjE5E3DbP4yovDs+hpWfbDBaAACbSXTijk700UXQmQ+VdSamyTosL7IyGd6slCYg1ZNrIm6jL+/aqO13622yDktdBsaZkiYbscw61JyAro+XLBmz7Y8MlZuLUPs0Mmbq41yTkXUGTP3i2q72uTbGRsv3e6yXdc8U3PYPIyrvxsaw8pMNRgsAAACgJzBaAAAAAD2B0QIAAADoCYwWAAAAQE9gtAAAtpIoGLdrQLnLGByWdeimpXFB6cKtU0RtjHQXgD9Md/VE09IoSSDSc01E27p48VihDdNdELtw2xTtJwVeu22Nsg6jeRGj4HZX97Dybp0zhajtke6Oj2HlI70LUR1Ox2gBAGwl0Ym+68UxKu+QyXAn9ciUxLpfZ6xH9XjdtTHKuIt0pwlnhISyFHNNxEarzCIUrg/Ulmj/OeMbEW3TqMZSVP9MIGp7pEd9FpWP9FHg6sZoAQAAAPQERgsAAACgJzBaAAAAAD2B0QIAAADoCYwWAMBW4gJgRTRtisuWk9ZlWhrNz+eCvp0mosDxKIjdZf819ZRtV0C2q7/Ry/KalsdNzRPNgagsPxfwrDY63fWjcEHv2neujU09pa51ujZ2nWon0qOg90h36xSu7mF6F1SHq2dL9Fwbrpf7WrgxEJWPsnibNkZ62Z5Id3VjtAAAthJ3whXRRdBdNFWHMz1R3TJf7jP36IFhumtLU75sy7B6ovKujZHJiHSnCddfIjZOpdFqyvttcvtPbYkM9MzIOoz0ySdqY3fd91lcvpvuiMo6HaMFAAAA0BMYLQAAAICewGgBAAAA9ARGCwAAAKAnMFoAAFuJC4AVUaCyC2xWkLkL4tachi6TcHx8RbVkyXihL11aaiIKBI+C2F0bo/JR1qECu1151e3qjzL3VNYFPEt3weOu7i3RXeC/yrptGp7NVo6PKPDfbb+I2hiVd22JdGmuHrUvKt9Fj/og0l1bhKs71ZNrTfmynq5tTJ85LdJzDaMFAAAA0BMYLQAAAICewGgBAAAA9ARGCwAAAKAnMFoAAAAAPYHRAgDYSlymkXDZTcJN4RLN87d48dI68zDXlXWo+Q5zfcmSsUITcdZhuU7RJctNmsvEU6agKx9nbCkTr+yzKEMv0p22Jbrbf12z1iI9qsf1l4j2h+v3rnq0/9Q+t94oa1R1uHpUf9QHTnfHRyqfa8LVEZWP+j3aT1ui5xpGCwAAAKAnMFoAAAAAPYHRAgAAAOgJjBYAAABAT2C0AAAAAHoCowXQAy7zBGYv0f6OMshcZlaUdahswQULyjkQNc/h4sVlhqGbF1EsXOizDqNsRJdVJlzGljS3rVFmVkRUPtKnE1EbIz3KfnP9KNyYEW5siGgcuDGjuhcuLDNbI13rdOuN9GhOSm2r06NtjfrG1RHpUb9HerT/hum5htECAAAA6AmMFkwJV1xx3WD51lvvHKD3F1/81kJzpM+f+tTD6vdnnXVO8b38/cqVqwtNv7ba7/WrJi/Trit/f9NN7yraljjqqGOq005bX7lZ5PN6Lrnk6kJL69NzZW644bb6ve5kuDLiHe94T/0+/fJLn5199jn160knnV58L38PAACjA6MFk86qVWurW2557+B9+wL/hjdcbHXHQQcdXL+eeOJLBpr+elmxYrWt4/DDn1sbrfzvmXaZ5z//RVY/+ugXDpZ32OGx9etNN727fnW3yhMyPnodH19efJbY1HbmZdq3t9v68cefMlhev/7c+vX662+pDj30WROMVl73jTfeNliO/l4CAIAtA6MFk47MwdjY8mrt2h0G79OdmLxc/t02yWi1cUarXXf7jtbee+9fa5deuqGoJ33XLb/0pS+bUHfbNOao7ptvvqNe3mefJxaf53XLFCUtkT675pq3F33i2njddTfXaFlG67jjTrZ3tNp3vVz/AwDA1oPRgkknXej1V1h6r9e3vW3iX3CbuvAno3XAAU8ZaM5oKTjx2msb47GpO1rtwM+2/prXXDBYfsIT9qlfdUdr++0XVKef3hgvx047Pa5ev0xdFMi5qe0Uugvoyk9s4xsLXUZLr+ed17R/2B2tzWkHAAB0A6MFk8q++x4wWE4X9vS6//4HVnvssVfxeYRin8488xXVm998Zf1+v/0Oqp7znH+vjjnm+OqII543oY4TTjilnhdORktl9Hkqs+uue1SvetXrqw0bbqra86y11y+zdPzxp1avf/2bB5qMlv7Ge93rLiralmKyVIe26corb6hNmZvDS2WOOea4CetL7UttvOCCS6unPOXQ+u7UkUcePSij76Qy4u1vv70655zzC6OV3sto5XUnoxVlKcFGoqykyERH8wi6TD/9besyvObPX1ST66pbYyrX8x8SCVe3cFlow+qJ+sBlW0FM1GfxWCr3tXBjSURzXurfhFzTXJqaOzPXVbebT1PnCne+iMZqlHUY6dG2RpmwUZ85XedmFzMb7Y+uuDowWgAAAAA9gdECAAAA6AmMFgAAAEBPYLQAAAAAegKjBQAAANATGC2AFtF8V8p2cdkkyvpy5aOsmSgLzWXHCFe3cG2B0RH1r9tPKhvt7+hhtu7BsMrwcpmBqtvVrzHp2uMyEYXLBhOuLU09PsMr6hsYDW6fCjcGRJx12MwgkbNs2cpC07hrPxYnocxTl42ojEaX1dhkzpbtUT0u00/b5PRo7HXVXV9G53gxirHt6sBoAQAAAPQERgsAAACgJzBaAACzgIsvvqTQAGDqwWjBlPGPf/yf/T97KlGb0vJNN73d6m0+9KEPV89//jFDywxjl10eV33uc58v9Db/+79/rl/32mufLVrHKNB+cjEZm+Jzn7u7+vnPf1HoU8WnPvWZas89966Xjz763yd8Noq+ffOb31IdddTGJ/VPFmr7M57RzJO5ObTH9taimJxhk6YnUv/+6le/rl8VVzOKPm9z2GHPtDFCAFMJRgumhOc856jqa1/7evX3v/+j+GzZshXV0qU+kFPoBL1ixapCTyRD0C6zfPnKCcGkbdOg+tJn6cSvz7WcyuUmQxeWxYuXVh/+8EcHRqtdRid7rT83kmqHtq+tKTBT60/fV5n0mabsSUarvY7UPyo78cLSBGZr3e2Az/S9NWvWTXgvhpko6Qrozsuo7pUry32goNexsY1TdsybN39CQPj2288bbH+qL031ou+5gF9tSzuoVdOFJL3dvyqTPmvXnz5rv2o9b33r1XWZfN/n9bbRdqvvc2ORAtBzo6VA5fb+1ve1zhS8q3W391++rY60z1MQcT5W8+DipUvH6jakdebl0/ry7U6JG/n+SqT3j370Y6rddtt9wufap3n53Gi160ivWld7Hwq1SVNntctFzAajFQVqu+m7RBQI7hIrRBQk76baEa49zTQ2pa59Fem5JqK2R+Vd3SI6ZqJ6Ir0vMFowJaSTbv6L9q9/fbg655xzqzvvfF/18MN/K763004719857rgT6ld3gEkX73vfB+pXmbmXvezlE9al5XTQnnnm2dWrXvWaga6L5hvf+KZ6Wa95eS3ffvsd1cc/flf1iU98sjrmmBcOdL0uWrS4Xj7yyOfUrzvssGOtf/SjH6t+9rOfVxs2XDOhLfvv/8Q6Iyy1+5WvPLd+3W+/J9YnorbRSsZUnz/44C+qs89+Wb2stujksc8++9XvX/Si44vtFe95zx0T2iruuuuT1UknvXjwvr2uX/ziv6tvfevb1QknnDThIqfvv/Slp0+o58EHf1794Q9/rN70posG+llnrR/cPdG6pV922eX1fk5lNmy4tv7ea1/7ukHb2+s544yz6lf1tbQf/vBH/9yW9xYX5Xyb03Lqt8MOe0b9+uQnH1w98MBP6/176KFPH5QXt9/+ngnfzeu/7bZ31tuQxqf6PZmSttG68sqr6vJvecvFRbvSRfBvf/v7YAxLv+66G+rXD3/4I4PyGo/JyOmz889/ffX73/+h/rGi7+ZjtX3xuuOO91Zf+MKXHtlXZwzGUV5+7dpH1+/Xr2/GUroIaT/87nd/qMdtWneqV/z5z3+pX6+44qrq+utvGNSncaDtete73l1st17bRitp2j9aPv30Mya04eSTT63fp2Mib0PObDBa7pwmIlPifpyIaA5Ll3Wo48hnHS6ycyOqbld/pOsHhzOKMuRueyNDFemu7kb3dUf19AVGC6aEv/zlr/WrLvCf/eznBvqmTqRtnvrUQ6t3v/v2Qm/X8d///cvi87xMbrRcmbT8tKf964Q7NL/85a+Kvw6/9KUvVxdeuHHy6byOnP32299+nt5HRsuVzWkbxbb+6le/ZnAxyz8Tr3nNedWll142eP/0pz9jYLTy8um9XvOTfttoRd/bsOFaq+fl07bLaLV1913VmYxnW09GS8gAue+K9etfXtSdk7dPtI1W/vm9995Xv8pk5nXkZfP34k9/+t+wjCsv3B3jvHz+3fT+2GOPm6DrjuNvf/s/9fIVVzQTuSd93bod6uXFi5dUH/nIRwefte8cpHojo9Ve1ze+8c0Jn+flI2aD0YLZB0YLpoQ//enPj5igX9W0T57p17XYY48nFN/TiTt9ft99P6re+947izLt+kZttPTrvV2P7rzlRkvooqj3uvOg96tWrak+/vFPTPhuYpRGq90/uuNw0UVvseXFpz/9mfr1P//zv4rP/uu/fjbhveptG60c6fqVmO5MnHrqabXWNlq6O9auM31vw4ZrrZ6vI+nDjNapp76kvvuWyt533w+rxzzmsbUh1vvNNVovfOGL7N8LqR1pO/PPk9GS8fjd735ffDct77rrbvWv7ec97+gJ9ebbGn1f6MfKMLPc/p5o/5XeLp9/N73PjVb7s/Z32kbr2muvK7bjoIOePOE7m2O0vvvd7xfrce9zMFowHcFowaSTnyz16/W5zz2qXl69em1YLtfOO+/8kRit++//yWYbrcc9bvdqt932GOgPP/x3a7TcutqfK74rLY/SaLV1XYSHGS1pV111daGLZz/7iOree38weK+/gaI7Wollyzb+xaC/5WSaoztauuin9xs2XDuhnqRrW9u3+FMs1zCjlb7/wAP/OVhuX8S3xmi191lePjHsjlbbaOftyo2Ge9DpD394/4T37frzdSXaf51E5fPvpvfOaB111NHV+Piy6p57vj7QZLQUp5Xe62/g/HvtejFaMNfAaMGkku64tDVdQNsGQr/2X/CCFxblxI9+dH/1rGc9uzY73/vevVtltHS36bWvPb+ODYqM1imnnGr1Jz3pyfVJ/e67v1gYrU9+8tP1erUd+pvo3HNfVes/+9mDdX159mDbaCm2RWZOy69//RtqvYvR+p//+V39l+oeezy+rmuY0br//h9bvV3neee9tjr++BOrD3zgQwOjpb8HdXdK26cy6W8kLeuO0pOe9JRBvW2jdckll9X6H//4p+qhh34zKLNhw7XFetvL6o8U3yVtU0ZLfaT4Ni3feef7J9TXNlpPeMJe1ec//4W6r/L1OqMl1KeKE7zwwo1xaG3aRuv73/9Bfdfp8Y/fsyir920tZeDtu+/+m4wR2333x1c33nhTbX7bel5WaP2q8wlPmNgGLZ922kvrZfWV3mt/at+kwHNntKJ1STvxxCbOT+u8665PPbLeJl4w/14Xo3X44UfUZb773e9NiOtrf7cNRgumIxgtmHa85jWvrZ75zGcXekJ3ss45pzEvW0qK38nvHDQX140X2NSO/KJ71lln13+JRNNf6O7HJZdcWuhKwZd5yXW1I1042vFd0XQqeXvaKC7rzDPPKnSHu1i1UWxWylRs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>

[image2]: <data:image/png;base64,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16jirDpBC4BZ0ihoVY444sjiJjipXl+7Uxfr27RYg1bG/oMOWlvMH0ecVydoATBLGgetro0bv1LcDJuIc3uJPW1a7EGr8tWvfq3oHUf1nq84s4k4t07QAmCWTBy0utauPbi4KY7iiis2FrP6ib1tErQmO8vKlfsVc5qKs+sELQBmSWtBK1q79pDOpk1f79x1192dRx55tHPPPfd2brnl1s6FF36iqGV67bPPCzrXXPPtzq9/fduO/44PPPBg54YbbuxccMGFRS0A8PfSghYAwGInaAEAJBG0AACSCFoAAEkELQCAJIIWAEASQQsAIImgBQCQRNACAEgiaAEAJBG0AACSCFoAAEkErTmwZs3BnQ9/+COd97znXzorVqwsrjPYypX/2Dn33PM7F110ceekk04uri8Ee++9b+e0097dWb/+ss7b3/7Ozk477VLUADB7GgetF75wVefZZ/82kT//+bHOd77z3c7znrdzMX+QOGdc27c/27n11v/qLF26vJjdhgsv/ESxs58Xv/iAon8SBx20ttjRdfHFny7qK7GubXHfMB/96DnFjH42bvxK0d9L7Mt21VWbijNEW7Y8VfT18tBDD3cOPfSwoh+A6TevQauXUf6ffOxpw9FHH1PsGceSJbsWM8fxwAMPFjObmOWgFfvGFee1OXtc/YLWnXf+rqgdx9atzxQzAZheUxe0Ktdd94NiX12sb1PcNYrvf//aYk5Tu+22RzF/HLMYtHbZZbKQWhdnz9XvGPUKWg8//EhR11ScDcB0msqgVfnrX7cWO7tibZu2bdte7BvkkksuLWZMqgoecc+oZi1ovelNby7qJxV3zMXvGMWg9eSTW4qaSa1atbr4PQGYLlMbtCo33PDzYm8l1mWIO3up3usV+9ry3e/+e7FvFLMUtNate2NR25aVK/eb098xqgetb3zjm8X1tuyxx7Li7wrA9JjqoFWJeyuxJsOw90w98cSTRU/b4s5RzErQWrJkt6KubXP5O0b1oBWv9VL9C+7GjV/e8cnKKmTH64PEvy0A0yMlaN188y1FfS9r1x5S9PYS++L1QbW97Lzzkh03ttgbxb6u/ff/p6K2l0Gfpnz+8/cp6nuJfcO0HbRibVvinl4GfSq0ei9brI+GvdevnzinLtYOM+xfPQf9b6RSXY89k5wHgLk1r0Gra1joifXx+qDaQW677faiv67f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>