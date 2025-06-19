import 'package:flutter/material.dart';

class GamingFeatureCard extends StatelessWidget {
  final String text;

  const GamingFeatureCard({super.key,required this.text});
  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 10),
      child: Container(
        height: 175,
        decoration: BoxDecoration(
          borderRadius: BorderRadius.circular(20),
          border: Border.all(color: Colors.white.withOpacity(0.5)),
          gradient: LinearGradient(
            begin: Alignment.topCenter,
            end: Alignment.bottomCenter,
            colors: [
              Colors.white.withOpacity(0.5),
              Colors.black.withOpacity(0.4), // Gradient effect
            ],
          ),
        ),
        padding: const EdgeInsets.all(10),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          mainAxisAlignment: MainAxisAlignment.center,
          children: <Widget>[
            Text(
              text,
              style: const TextStyle(
                color: Colors.white,
                fontSize: 14,
                // overflow: TextOverflow.fade
              ),
            ),
            const SizedBox(height: 8),
            const Icon(Icons.more_horiz, color: Colors.white, size: 24), // More icon
          ],
        ),
      ),
    );
  }
}
