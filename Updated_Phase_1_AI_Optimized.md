
# **Homepage Animation & Section Transition Spec (Sections 1–8, Pre-About)**

---

## Section 1: Hero / Intro

**Layout:** Fullscreen (100vh), no gutter, content centered.

**AI Directive:**
- Review current JS/CSS/HTML.
- Explicitly confirm if pin duration matches specs (continuous pin until About section).
- Provide explicit code suggestions for discrepancies.

**Outstanding Tasks:**
- Pin duration explicitly verified [Yes/No]: _____
  - [Prompt: "Brandon, clarify intended pin duration or provide current pin logic details: _____"]
- Headline exit animation explicitly triggers correctly [Yes/No]: _____
  - [Prompt: "Brandon, provide clarification or screenshots if exit animation behavior is unclear: _____"]

---

## Section 2: "Select Projects" Title

**AI Directive:**
- Verify explicit headline animation entry/exit logic.
- Confirm background inheritance explicitly from Section 1.

**Outstanding Tasks:**
- Explicit verification of pin logic and duration [Yes/No]: _____
- Explicit confirmation of headline animation entry/exit [Yes/No]: _____
  - [Prompt: "Brandon, clarify exact animation timing or provide expected animation reference explicitly: _____"]

---

## Sections 3–7: Project Sections (Repeatable Module)

**AI Directive:**
- Confirm explicitly image and title animations match provided references.
- Confirm explicitly the logic for dot indicators (sticky, click-scroll).

**Outstanding Tasks:**
- Project image animation explicitly matches specs [Yes/No]: _____
- Project title animation explicitly correct and swaps seamlessly [Yes/No]: _____
- Dot indicator logic explicitly implemented correctly [Yes/No]: _____
  - [Prompt: "Brandon, clarify dot indicator behavior explicitly or provide additional details: _____"]

---

## Section 8: Final Project Section (Pre-About “Sticky”)

**AI Directive:**
- Verify explicitly that pinning remains until fully covered by About.
- Confirm explicitly dot indicator behavior.

**Outstanding Tasks:**
- Explicit confirmation of sticky logic [Yes/No]: _____
- Explicit dot indicator sticky implementation verified [Yes/No]: _____

---

## General Animation Notes

- **Pinning:** All via GSAP ScrollTrigger explicitly.
- **Reveal Masking:** Explicit upward masks for text, GSAP + SplitText.
- **Dot Indicators:** Explicitly verify desktop-only display and positioning.

**AI Verification Checklist (Per Section):**
- [ ] GSAP correctly loaded.
- [ ] p5.js animations fully functional.
- [ ] ScrollTrigger explicitly matches timing/triggers provided.
- [ ] SplitText masks explicitly animate correctly.
- [ ] Responsive/mobile explicitly tested and verified.

---

## Explicit Known Issues Prompt

Before troubleshooting, explicitly consult `AI_AGENT_KNOWN_ISSUES.txt`:
- Issue matches known conflicts explicitly [Yes/No]: _____
- If "Yes," explicitly follow provided resolution.
- If "No," prompt Brandon explicitly for additional clarity.

---

## AI Clarity Confirmation

Explicitly restate your understanding before implementation:
- [Prompt: "Brandon, confirm or correct my explicit understanding of the animation directives: _____"]

---

## Order of Events (Scroll Flow)

Explicitly verify each event matches provided spec:
1. Hero intro explicitly animates in.
2. Scroll explicitly triggers hero copy mask-out.
3. "Select Projects" explicitly animates and pins.
4. Project sections explicitly animate in with seamless transitions.
5. Final project explicitly sticky until About overlaps.

---

## Development/Application Instructions

Explicitly use provided CSS classes (.brandon-*) and GSAP triggers.

**Explicit prompt if unclear:**
- [Prompt: "Brandon, explicitly confirm class naming conventions or trigger details: _____"]

---

## Explicit Next Steps

1. Explicit QA on Section 1/2 pin duration.
2. Explicit refinement of “Select Projects” to project title swap animation.
3. Explicit generalization and verification of project logic for Sections 3–7.
4. Explicit implementation and verification of Section 8 sticky logic.
5. Explicit mobile/responsive testing.

---

**Prompt Brandon explicitly for any missing details or unclear instructions throughout.**
